<?php

namespace Initbiz\Newsletter\EventHandlers;

use MailerLite\MailerLite;
use Initbiz\Newsletter\Models\Tag;
use Initbiz\Newsletter\Models\Settings;
use Initbiz\Newsletter\Models\Subscriber;

class MailerLiteHandler
{
    protected MailerLite $mailerLiteClient;

    public function subscribe($event)
    {
        if (Settings::get('enable_mailerlite_integration', false)) {
            $apiKey = Settings::get('mailerlite_api_key');
            $this->mailerLiteClient = new MailerLite(['api_key' => $apiKey]);

            $this->subscriberCreate($event);
            $this->subscriberUpdate($event);
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
            $data = $this->subscriberToMailerLiteSyntax($subscriber);

            $response = $this->mailerLiteClient->subscribers->create($data);

            $subscriber->setAdditionalData('mailerlite_id', $response['body']['data']['id']);
            $subscriber->save();
        });
    }

    protected function subscriberUpdate($event)
    {
        $event->listen('initbiz.newsletter.subscriberUpdate', function ($subscriber) {
            $data = $this->subscriberToMailerLiteSyntax($subscriber);
            $this->mailerLiteClient->subscribers->create($data);
        });
    }

    protected function subscriberDelete($event)
    {
        $event->listen('initbiz.newsletter.subscriberDelete', function ($subscriber) {
            $data = $this->subscriberToMailerLiteSyntax($subscriber);
            $mailerliteId = $data['id'] ?? null;
            if (!is_null($mailerliteId)) {
                $this->mailerLiteClient->subscribers->delete($data['id']);
            }
        });
    }

    protected function tagCreate($event)
    {
        $event->listen('initbiz.newsletter.tagCreate', function ($tag) {
            $data = $this->tagToMailerLiteSyntax($tag);

            $response = $this->mailerLiteClient->groups->create($data);

            $tag->setAdditionalData('mailerlite_id', $response['body']['data']['id']);
            $tag->save();
        });
    }

    protected function tagDelete($event)
    {
        $event->listen('initbiz.newsletter.tagDelete', function ($tag) {
            $data = $this->tagToMailerLiteSyntax($tag);
            $mailerliteId = $data['id'] ?? null;
            if (!is_null($mailerliteId)) {
                $this->mailerLiteClient->groups->delete($data['id']);
            }
        });
    }

    protected function subscriberTagsAttached($event)
    {
    }

    protected function subscriberTagsDetached($event)
    {
    }

    protected function subscriberToMailerLiteSyntax(Subscriber $subscriber): array
    {
        $groups = [];
        foreach ($subscriber->tags as $tag) {
            $tagData = $this->tagToMailerLiteSyntax($tag);
            if (isset($tagData['id'])) {
                $groups[] = $tagData['id'];
            }
        }

        $fields  = [
            'token' => $subscriber->token,
        ];

        if (!empty($subscriber->zip)) {
            $fields['z_i_p'] = $subscriber->zip;
        }

        if (!empty($subscriber->phone)) {
            $fields['phone'] = $subscriber->phone;
        }

        if (!empty($subscriber->first_name)) {
            $fields['name'] = $subscriber->first_name;
        }

        if (!empty($subscriber->last_name)) {
            $fields['last_name'] = $subscriber->last_name;
        }

        if (!empty($subscriber->company)) {
            $fields['company'] = $subscriber->company;
        }

        if (!empty($subscriber->city)) {
            $fields['city'] = $subscriber->city;
        }

        $fields = array_merge($fields, $subscriber->getAdditionalDataKeyValue());

        $data = [
            'email' => $subscriber->email,
            'subscribed_at' => $subscriber->created_at->format('Y-m-d H:i:s'),
            'fields' => $fields,
            'groups' => $groups,
        ];

        $mailerliteId = $subscriber->getAdditionalData('mailerlite_id');

        if (!is_null($mailerliteId)) {
            $data['id'] = $mailerliteId;
        }

        return $data;
    }

    public function tagToMailerLiteSyntax(Tag $tag): array
    {
        $mailerliteId = $tag->getAdditionalData('mailerlite_id');

        $data = [
            'name' => $tag->name,
        ];

        if (!is_null($mailerliteId)) {
            $data['id'] = $mailerliteId;
        }

        return $data;
    }
}
