<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Api\Controllers;

use Response;
use Validator;
use ValidationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Initbiz\Newsletter\Models\Settings;
use Initbiz\Newsletter\Models\Tag;
use Initbiz\Newsletter\Models\Subscriber;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MailerLiteController extends Controller
{
    public function handle(Request $request)
    {
        $secret = Settings::get('mailerlite_webhook_secret');
        if (empty($secret)) {
            return Response::make('Webhook not configured properly');
        }

        $this->validateRequest($request, $secret);

        $data = $request->all();

        // MailerLite sends one or many events in a single webhook
        if (!isset($data['events'])) {
            $data = [
                'events' => $data
            ];
        }

        foreach ($data['events'] as $eventData) {
            try {
                $this->validateEventData($eventData);
            } catch (ValidationException $ex) {
                // We want to silently continue on all enabled webhooks
                trace_log($ex->getMessage());
                // We will continue if someone enables webhook that we don't handle
                continue;
            }

            // MailerLite sends the name in "event" but for some in "type" O.o
            $eventName = $eventData['event'] ?? $eventData['type'];
            // subscriber.removed_from_group -> SubscriberRemovedFromGroup
            $methodName = studly_case(str_replace('.', '-', $eventName));
            $this->$methodName($eventData);
        }

        return Response::make('ok');
    }

    public function subscriberCreated(array $data): void
    {
        $subscriber = $this->subscriberFromMailerLiteSyntax($data);
        $subscriber->save();
    }

    public function subscriberUpdated(array $data): void
    {
        $subscriber = $this->subscriberFromMailerLiteSyntax($data);
        $subscriber->save();
    }

    public function subscriberAddedToGroup(array $data): void
    {
        $subscriber = $this->subscriberFromMailerLiteSyntax($data);

        $tag = Tag::where('name', $data['group']['name'])->first();
        if (!$tag) {
            $tag = new Tag();
            $tag->setAdditionalData('mailerlite_id', $data['group']['id']);
            $tag->save();
        }

        $subscriber->attachTags($tag);
    }

    public function subscriberRemovedFromGroup(array $data): void
    {
        $subscriber = $this->subscriberFromMailerLiteSyntax($data);

        $tag = Tag::where('name', $data['group']['name'])->first();
        if (!$tag) {
            $tag = new Tag();
            $tag->setAdditionalData('mailerlite_id', $data['group']['id']);
            $tag->save();
        }

        $subscriber->detach($tag->id);
    }

    public function subscriberUnsubscribed(array $data): void
    {
        $subscriber = $this->subscriberFromMailerLiteSyntax($data);
        $subscriber->save();
    }

    public function subscriberDeleted(array $data): void
    {
        $subscriber = $this->subscriberFromMailerLiteSyntax($data);
        $subscriber->delete();
    }

    // Helpers

    public function validateRequest(Request $request, string $secret): void
    {
        $data = $request->all();
        $signature = $request->header('signature');
        if ($signature !== hash_hmac('sha256', json_encode($data), $secret)) {
            throw new BadRequestHttpException("Invalid signature");
        }
    }

    public function validateEventData(array $eventData): void
    {
        $supportedEvents = [
            'subscriber.created',
            'subscriber.updated',
            'subscriber.added_to_group',
            'subscriber.removed_from_group',
            'subscriber.unsubscribed',
            'subscriber.deleted',
        ];

        $rules = [
            'event' => 'in:' . implode(',', $supportedEvents),
            'type' => 'in:' . implode(',', $supportedEvents),
        ];

        $validator = Validator::make($eventData, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Convert MailerLite request fields to Subscriber instance
     *
     * @param array $data MailerLite request syntax
     * @return Subscriber
     */
    protected function subscriberFromMailerLiteSyntax(array $data): Subscriber
    {
        // Sometimes it's directly in "fields", sometimes in the subscriber entry
        if (isset($data['subscriber'])) {
            $data = $data['subscriber'];
        }

        $subscriber = Subscriber::where('email', $data['email'])->first();
        if (!$subscriber) {
            $subscriber = new Subscriber();
            $subscriber->email = $data['email'];
            $subscriber->setAdditionalData('mailerlite_id', $data['id']);
            $subscriber->created_at = $data['created_at'];
            $subscriber->updated_at = $data['updated_at'];
            $subscriber->unsubscribed_at = $data['unsubscribed_at'];
        }

        $subscriber->changedUsingIntegration = true;

        $fields = $data['fields'];

        if (!empty($fields['z_i_p'])) {
            $subscriber->zip = $fields['z_i_p'];
        }

        if (!empty($fields['phone'])) {
            $subscriber->phone = $fields['phone'];
        }

        if (!empty($fields['name'])) {
            $subscriber->first_name = $fields['name'];
        }

        if (!empty($fields['last_name'])) {
            $subscriber->last_name = $fields['last_name'];
        }

        if (!empty($fields['company'])) {
            $subscriber->company = $fields['company'];
        }

        if (!empty($fields['city'])) {
            $subscriber->city = $fields['city'];
        }

        $subscriber->confirmed = $data['status'] === 'unconfirmed';

        if ($subscriber->unsubscribed_at) {
            $data['unsubscribed_at'] = $subscriber->unsubscribed_at->format('Y-m-d H:i:s');
        }

        return $subscriber;
    }
}
