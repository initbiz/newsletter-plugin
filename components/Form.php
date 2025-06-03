<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Components;

use Lang;
use Mail;
use Event;
use Request;
use ValidationException;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Tag;
use October\Rain\Database\Collection;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Models\Settings;
use Initbiz\Newsletter\Models\Subscriber;

class Form extends ComponentBase
{
    /**
     * List of checkboxes set in the backend
     *
     * @var null|array
     */
    public $checkboxes;

    /**
     * List of inputs selected in the component to be rendered
     *
     * @var null|array
     */
    public $selectedInputs;

    /**
     * Text displayed on the button
     *
     * @var null|string
     */
    public $buttonText;

    /**
     * Directory to get view from (handy for snippets)
     *
     * @var null|string
     */
    public $customViewPath;

    public function componentDetails()
    {
        return [
            'name' => 'initbiz.newsletter::lang.form_component.name',
            'description' => 'initbiz.newsletter::lang.form_component.description',
            'snippetAjax' => true,
        ];
    }

    public function defineProperties()
    {
        return [
            'confirmAutomatically' => [
                'title' => 'initbiz.newsletter::lang.form_component.confirm_automatically',
                'type' => 'checkbox',
                'default' => 0,
            ],

            'tags' => [
                'title' => 'initbiz.newsletter::lang.form_component.tags',
                'type' => 'set',
            ],

            'inputs' => [
                'title' => 'initbiz.newsletter::lang.form_component.inputs',
                'type' => 'set',
                'default' => [
                    'email',
                ],
            ],

            'buttonText' => [
                'title' => 'initbiz.newsletter::lang.form_component.button_text',
                'type' => 'string',
                'default' => 'initbiz.newsletter::lang.form.button_text'
            ],

            'ref' => [
                'title' => 'initbiz.newsletter::lang.form_component.ref',
                'description' => 'initbiz.newsletter::lang.form_component.ref_description',
                'type' => 'string',
            ],

            'customViewPath' => [
                'title' => 'initbiz.newsletter::lang.form_component.custom_view_path',
                'description' => 'initbiz.newsletter::lang.form_component.custom_view_path_description',
                'type' => 'string',
            ],
        ];
    }

    public function getTagsOptions(): array
    {
        return Tag::all()->pluck('name', 'slug')->toArray();
    }

    public function getInputsOptions(): array
    {
        $inputs = [];
        foreach (Subscriber::getFillableAttributes() as $fillableAttribute => $def) {
            $inputs[$fillableAttribute] = $def['label'];
        }
        return $inputs;
    }

    public function onRun()
    {
        $this->checkboxes = $this->page['checkboxes'] = Checkbox::all();
        $this->selectedInputs = $this->getSelectedInputs();
        $this->buttonText = $this->property('buttonText');
        $this->customViewPath = $this->property('customViewPath');
    }

    public function onRender()
    {
        $this->customViewPath = $this->property('customViewPath');
        if (empty($this->customViewPath)) {
            return;
        }

        /*
         * Make it possible to overwrite the view no matter what alias we're in
         * It's handy when placing many forms on a single page using snippets
         */
        try {
            return $this->renderPartial($this->customViewPath . '/default', ['__SELF__' => $this]);
        } catch (\Cms\Classes\CmsException $th) {
        }
    }

    /**
     * Get inputs with their definitions using inputs property
     *
     * @return array
     */
    public function getSelectedInputs(): array
    {
        $selectedInputs = (array) $this->property('inputs');

        $selectedInputsDefs = [];
        foreach (Subscriber::getFillableAttributes() as $fillableAttribute => $def) {
            if (in_array($fillableAttribute, $selectedInputs, true)) {
                $selectedInputsDefs[$fillableAttribute] = $def;
            }
        }

        return $selectedInputsDefs;
    }

    // AJAX handlers

    /**
     * AJAX Handler to subscribe the e-mail
     *
     * @return void
     */
    public function onSubscription(array $data = [])
    {
        if (empty($data)) {
            $data = post();
        }

        $requiredCheckboxes = Checkbox::required()->get();
        $checkedCheckboxes = Checkbox::whereIn('slug', array_keys($data))->get();
        $checkedCheckboxesIds = $checkedCheckboxes->pluck('id')->toArray();

        foreach ($requiredCheckboxes as $requiredCheckbox) {
            if (!in_array($requiredCheckbox->id, $checkedCheckboxesIds, true)) {
                throw new ValidationException([
                    'requiredCheckboxes' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.checkbox_validation_failed')
                ]);
            }
        }

        /** @var Subscriber */
        $subscriber = Subscriber::where('email', $data['email'])->first();

        $currentUrl = Request::url();
        if (!$subscriber) {
            $subscriber = new Subscriber();
            $subscriber->setAdditionalField('registration_form_ref', $this->getRef());
            $subscriber->setAdditionalField('registration_url', $currentUrl);
        }

        $subscriber->fill($data);

        if ($this->property('confirmAutomatically', false)) {
            $subscriber->confirmed = true;
        }

        if (isset($data['additional_fields']) && is_array($data['additional_fields'])) {
            foreach ($data['additional_fields'] as $key => $value) {
                $subscriber->setAdditionalField($key, $value);
            }
        }

        $subscriber->save();

        $subscriber->attachCheckboxes($checkedCheckboxes);

        $tagsSlugs = $this->property('tags');
        $tagsToGive = new Collection();
        if (!empty($tagsSlugs)) {
            $tagsToGive = Tag::whereIn('slug', $tagsSlugs)->get();
            $subscriber->attachTags($tagsToGive);
        }

        $settings = Settings::instance();
        if ($settings->send_activation_email) {
            $this->sendActivationEmail($subscriber);
        }

        $eventParams = [
            $this->getRef(),
            $data,
            $currentUrl,
            $subscriber,
            $checkedCheckboxes,
            $tagsToGive,
        ];

        $this->fireEvent('form.submitted', $eventParams);
        Event::fire('initbiz.newsletter.formSubmitted', array_merge([$this], $eventParams));

        $result = [
            'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_success')
        ];

        return $result;
    }

    /**
     * Send activation email to $subscriber
     * @return void
     */
    protected function sendActivationEmail($subscriber)
    {
        $settings = Settings::instance();

        $options = [
            'recipient_email' => $subscriber->email,
            'recipient_name' => empty($subscriber->full_name) ? $subscriber->email : $subscriber->full_name,
            'subject' => Lang::get('initbiz.newsletter::lang.mail.activation_subject'),
            'template' => 'initbiz.newsletter::mail.subscription',
            'token' => $subscriber->token,
            'activationLink' => $settings->getNewsletterManagementUrl($subscriber->email, $subscriber->token)
        ];

        Mail::queue($options['template'], $options, function ($message) use ($options) {
            $message->to($options['recipient_email'], $options['recipient_name']);
            $message->subject($options['subject']);
        });
    }

    /**
     * String identifying this form - reference, if not set - alias is the default ref
     *
     * @return string
     */
    public function getRef(): string
    {
        $ref = $this->property('ref', $this->alias);

        if (empty($ref)) {
            $ref = 'newsletter';
        }

        return $ref;
    }
}
