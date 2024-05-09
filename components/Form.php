<?php

namespace Initbiz\Newsletter\Components;

use Lang;
use Mail;
use ValidationException;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Tag;
use Initbiz\Newsletter\Classes\Helpers;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Classes\SendEmail;
use Initbiz\Newsletter\Models\Settings;
use Initbiz\Newsletter\Models\Subscriber;

class Form extends ComponentBase
{
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
            'tags' => [
                'title' => 'initbiz.newsletter::lang.formComponent.tags',
                'type' => 'set',
            ]
        ];
    }

    public function getTagsOptions(): array
    {
        return Tag::all()->pluck('name', 'slug')->toArray();
    }

    public function onRun()
    {
        $this->page['checkboxes'] = Checkbox::all();
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

        $subscriber = Subscriber::where('email', $data['email'])->first();

        if (!$subscriber) {
            $subscriber = new Subscriber();
        }

        $subscriber->fill($data);
        $subscriber->save();

        $subscriber->attachCheckboxes($checkedCheckboxes);

        $tagsToGive = Tag::whereIn('slug', $this->property('tags'))->get();
        $subscriber->attachTags($tagsToGive);

        $this->sendActivationEmail($subscriber);

        $result = [
            'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_success')
        ];

        return $result;
    }

    /**
     * Send activation email to $this->subscriber
     * @return void
     */
    protected function sendActivationEmail($subscriber)
    {
        $settings = Settings::instance();

        $options = [
            'recipient_email' => $subscriber->email,
            'recipient_name' => (empty($subscriber->full_name))? $subscriber->email: $subscriber->full_name,
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
