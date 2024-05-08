<?php

namespace Initbiz\Newsletter\EventHandlers;

use MailerLite\MailerLite;
use Initbiz\Newsletter\Models\Settings;

class MailerLiteHandler
{
    protected MailerLite $mailerLiteClient;

    public function subscribe($event)
    {
        if (Settings::get('enable_mailerlite_integration', false)) {
            $apiKey = Settings::get('mailerlite_api_key');
            $this->mailerLiteClient = new MailerLite(['api_key' => $apiKey]);

            $this->subscriberCreate($event);
            $this->subscriberSave($event);
            $this->subscriberDelete($event);
            $this->tagCreate($event);
            $this->tagDelete($event);
            $this->subscriberTagsAttached($event);
            $this->subscriberTagsDetached($event);
        }
    }

    protected function subscriberCreate($event)
    {
        $event->listen('initbiz.newsletter.subscriberCreate', function ($subscriber) {
            $data = [
                'email' => $subscriber->email,
            ];
    
            $this->mailerLiteClient->subscribers->create($data);
        });
    }

    protected function subscriberSave($event)
    {
    }

    protected function subscriberDelete($event)
    {
    }

    protected function tagCreate($event)
    {
    }

    protected function tagDelete($event)
    {
    }

    protected function subscriberTagsAttached($event)
    {
    }

    protected function subscriberTagsDetached($event)
    {
    }
}
