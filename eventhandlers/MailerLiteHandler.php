<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\EventHandlers;

use MailerLite\MailerLite;
use Initbiz\Newsletter\Models\Tag;
use Initbiz\Newsletter\Models\Settings;
use Initbiz\Newsletter\Models\Subscriber;

/**
 * Integration with MailerLite basing on internal events
 */
class MailerLiteHandler
{
    protected MailerLite $mailerLiteClient;

    public function subscribe($event)
    {
        if (!Settings::get('enable_mailerlite_integration', false)) {
            return;
        }

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

    protected function subscriberCreate($event)
    {
        $event->listen('initbiz.newsletter.subscriberCreate', function ($subscriber) {
            if ($subscriber->changedUsingIntegration) {
                return;
            }

            $data = $this->subscriberToMailerLiteSyntax($subscriber);

            $response = $this->mailerLiteClient->subscribers->create($data);

            $subscriber->setAdditionalData('mailerlite_id', $response['body']['data']['id']);
            $subscriber->save();
        });
    }

    protected function subscriberUpdate($event)
    {
        $event->listen('initbiz.newsletter.subscriberUpdate', function ($subscriber) {
            if ($subscriber->changedUsingIntegration) {
                return;
            }

            $data = $this->subscriberToMailerLiteSyntax($subscriber);
            $this->mailerLiteClient->subscribers->create($data);
        });
    }

    protected function subscriberDelete($event)
    {
        $event->listen('initbiz.newsletter.subscriberDelete', function ($subscriber) {
            if ($subscriber->changedUsingIntegration) {
                return;
            }

            $mailerliteId = $subscriber->getAdditionalData('mailerlite_id');
            if (!is_null($mailerliteId)) {
                $this->mailerLiteClient->subscribers->delete($mailerliteId);
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
            $mailerliteId = $tag->getAdditionalData('mailerlite_id');
            if (!is_null($mailerliteId)) {
                $this->mailerLiteClient->groups->delete($mailerliteId);
            }
        });
    }

    protected function subscriberTagsAttached($event)
    {
        $event->listen('initbiz.newsletter.subscriberTagsAttached', function (Subscriber $subscriber, $tags) {
            if ($subscriber->changedUsingIntegration) {
                return;
            }

            $data = $this->subscriberToMailerLiteSyntax($subscriber);

            $newGroups = [];
            foreach ($tags as $tag) {
                $mailerLiteId = $tag->getAdditionalData('mailerlite_id');
                if (!$mailerLiteId) {
                    continue;
                }
                $newGroups[] = $mailerLiteId;
            }
            $data['groups'] = array_merge($newGroups, $data['groups']);

            $this->mailerLiteClient->subscribers->create($data);
        });
    }

    protected function subscriberTagsDetached($event)
    {
        $event->listen('initbiz.newsletter.subscriberTagsDetached', function (Subscriber $subscriber, $tags) {
            if ($subscriber->changedUsingIntegration) {
                return;
            }

            $data = $this->subscriberToMailerLiteSyntax($subscriber);
            $removedTagsIds = $tags->pluck('id')->toArray();

            $newGroups = [];
            foreach ($subscriber->tags as $tag) {
                if (in_array($tag->id, $removedTagsIds)) {
                    continue;
                }

                $mailerLiteId = $tag->getAdditionalData('mailerlite_id');
                if (!$mailerLiteId) {
                    continue;
                }

                $newGroups[] = $mailerLiteId;
            }

            $data['groups'] = $newGroups;

            $this->mailerLiteClient->subscribers->create($data);
        });
    }

    /**
     * Convert subscriber to syntax accepted by MailerLite
     *
     * @param Subscriber $subscriber
     * @return array
     */
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

        $fields = array_merge($fields, $subscriber->getAdditionalFieldsKeyValue());

        $status = ($subscriber->confirmed) ? 'active' : 'unconfirmed';

        $data = [
            'email' => $subscriber->email,
            'status' => $status,
            'subscribed_at' => $subscriber->created_at->format('Y-m-d H:i:s'),
            'fields' => $fields,
            'groups' => $groups,
        ];

        if ($subscriber->unsubscribed_at) {
            $data['unsubscribed_at'] = $subscriber->unsubscribed_at->format('Y-m-d H:i:s');
        }

        return $data;
    }

    /**
     * Convert tag to MailerLite syntax
     *
     * @param Tag $tag
     * @return array
     */
    protected function tagToMailerLiteSyntax(Tag $tag): array
    {
        $data = [
            'name' => $tag->name,
        ];

        return $data;
    }
}
