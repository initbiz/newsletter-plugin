<?php

namespace Initbiz\Newsletter\Components;

use Lang;
use Mail;
use ValidationException;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Tag;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Models\Settings;
use Initbiz\Newsletter\Models\Subscriber;

class Form extends ComponentBase
{
    public $checkboxes;

    public $selectedInputs;

    public function componentDetails()
    {
        return [
            'name'        => 'NewsletterForm',
            'description' => 'Newsletter Form component'
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
            'input' => [
                'title' => 'initbiz.newsletter::lang.form_component.inputs',
                'type' => 'set',
            ],
            'inputOrder' => [
                'title' => 'initbiz.newsletter::lang.form_component.inputs',
                'type' => 'set',
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
    }

    public function onRender()
    {
        // Use the default template from the theme, otherwise fallback to default behavior
        try {
            return $this->renderPartial('newsletterform/default', ['__SELF__' => $this]);
        } catch (\Cms\Classes\CmsException $th) {
            return null;
        }
    }

    public function getSelectedInputs(): array
    {
        $selectedInputs = (array) $this->property('inputs');

        $selectedInputsDefs = [];
        foreach (Subscriber::getFillableAttributes() as $fillableAttribute => $def) {
            if (in_array($fillableAttribute, $selectedInputs)) {
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
            if (!in_array($requiredCheckbox->id, $checkedCheckboxesIds)) {
                throw new ValidationException([
                    'requiredCheckboxes' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.checkbox_validation_failed')
                ]);
            }
        }

        /** @var Subscriber */
        $subscriber = Subscriber::where('email', $data['email'])->first();

        if (!$subscriber) {
            $subscriber = new Subscriber();
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
        if (!empty($tagsSlugs)) {
            $tagsToGive = Tag::whereIn('slug', $tagsSlugs)->get();
            $subscriber->attachTags($tagsToGive);
        }

        $settings = Settings::instance();
        if ($settings->send_activation_email) {
            $this->sendActivationEmail($subscriber);
        }

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
}
