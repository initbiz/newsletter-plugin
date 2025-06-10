<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Tests\Unit\Api\Controllers;

use PluginTestCase;
use Illuminate\Http\Request;
use October\Rain\Exception\ValidationException;
use Initbiz\Newsletter\Api\Controllers\MailerLiteController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Initbiz\Newsletter\Models\Subscriber;
use Initbiz\Newsletter\Models\Tag;

/**
 * for both example webhooks secret is: oOUngq53Iy
 * for webhook-1.json signature is: 97b447b4c12b16bb50653eb617565bb3032a887dd37d161381e277cbddacc5bc
 * for webhook-2.json signature is: eee922eeb404a9208570644d889bb8c9f0b3d47ba45d2e2250b0598e10491057
 * for webhook-3.json signature is: ad43cc00d87425f526433714f7af5738613890994e70d6fa1e9c4baa0b6b0ae1
 * for webhook-4.json signature is: ddc6a20e2d4268f126bd8818515d91a4f3e5e67555141bd7b4da841c5024de38
 * for webhook-5.json signature is: 0726cab6e0adfe6e87e8bfc686f7e37fa134883978b09584395f76a6cef26b36
 */
class MailerLiteControllerTest extends PluginTestCase
{
    public function testValidateRequestDoesntThrowExceptionIfValid()
    {
        // Mock from real MailerLite webhook request
        $secret = 'oOUngq53Iy';
        $contents = file_get_contents(plugins_path('initbiz/newsletter/tests/fixtures/webhook-2.json'));
        $mockRequest = Request::create('/path', 'POST');
        $mockRequest->headers->set('signature', '97b447b4c12b16bb50653eb617565bb3032a887dd37d161381e277cbddacc5bc');
        $mockRequest->request->add(json_decode($contents, true));

        $controller = new MailerLiteController();
        $this->expectNotToPerformAssertions();
        $controller->validateRequest($mockRequest, $secret);
    }

    public function testValidateRequestThrowsExceptionIfNotValid()
    {
        // Mock from real MailerLite webhook request
        $secret = 'oOUngq53Iy';
        $contents = file_get_contents(plugins_path('initbiz/newsletter/tests/fixtures/webhook-2.json'));
        $mockRequest = Request::create('/path', 'POST');
        $mockRequest->headers->set('signature', 'anything');
        $mockRequest->request->add(json_decode($contents, true));

        $controller = new MailerLiteController();
        $this->expectException(BadRequestHttpException::class);
        $controller->validateRequest($mockRequest, $secret);
    }

    public function testValidateEventDataWillNotThrowExceptionIfValid()
    {
        $contents = file_get_contents(plugins_path('initbiz/newsletter/tests/fixtures/webhook-1.json'));
        $data = json_decode($contents, true);

        $controller = new MailerLiteController();
        $this->expectNotToPerformAssertions();
        $controller->validateEventData($data);
    }

    public function testValidateEventDataWillThrowExceptionIfNotValid()
    {
        $contents = file_get_contents(plugins_path('initbiz/newsletter/tests/fixtures/webhook-1.json'));
        $data = json_decode($contents, true);

        $data['event'] = 'anything';
        $controller = new MailerLiteController();
        $this->expectException(ValidationException::class);
        $controller->validateEventData($data);
    }

    public function testSubscriberCreated()
    {
        $contents = file_get_contents(plugins_path('initbiz/newsletter/tests/fixtures/webhook-2.json'));
        $data = json_decode($contents, true);

        $eventData = [];
        foreach ($data['events'] as $eventData) {
            $eventName = $eventData['event'] ?? $eventData['type'];
            if ($eventName === 'subscriber.created') {
                break;
            }
        }

        $controller = new MailerLiteController();
        $controller->subscriberCreated($eventData);

        $this->assertEquals(1, Subscriber::count());
    }

    public function testSubscriberUpdated()
    {
        $contents = file_get_contents(plugins_path('initbiz/newsletter/tests/fixtures/webhook-2.json'));
        $data = json_decode($contents, true);

        $subscriber = new Subscriber();
        $subscriber->email = "qqqqffqq@init.biz";
        $subscriber->save();

        $eventData = [];
        foreach ($data['events'] as $eventData) {
            $eventName = $eventData['event'] ?? $eventData['type'];
            if ($eventName === 'subscriber.updated') {
                break;
            }
        }

        $controller = new MailerLiteController();
        $controller->subscriberUpdated($eventData);

        $subscriber = Subscriber::where('email', 'qqqqffqq@init.biz')->first();
        $this->assertEquals("tomasza", $subscriber->first_name);
    }

    public function testSubscriberAddedToGroup()
    {
        $contents = file_get_contents(plugins_path('initbiz/newsletter/tests/fixtures/webhook-2.json'));
        $data = json_decode($contents, true);

        $subscriber = new Subscriber();
        $subscriber->email = "qqqqffqq@init.biz";
        $subscriber->save();

        $eventData = [];
        foreach ($data['events'] as $eventData) {
            $eventName = $eventData['event'] ?? $eventData['type'];
            if ($eventName === 'subscriber.added_to_group') {
                break;
            }
        }

        $controller = new MailerLiteController();
        $controller->subscriberAddedToGroup($eventData);

        $subscriber = Subscriber::where('email', 'qqqqffqq@init.biz')->first();
        $this->assertEquals(1, $subscriber->tags->count());
    }

    public function testSubscriberRemovedFromGroup()
    {
        $subscriber = new Subscriber();
        $subscriber->email = "qqqqffvvgqq@init.biz";
        $subscriber->save();

        $tag = new Tag();
        $tag->name = "Darmowa konsultacja";
        $tag->save();

        $subscriber->tags()->add($tag);

        $subscriber = Subscriber::where('email', 'qqqqffvvgqq@init.biz')->first();
        $this->assertEquals(1, $subscriber->tags->count());

        $contents = file_get_contents(plugins_path('initbiz/newsletter/tests/fixtures/webhook-3.json'));
        $data = json_decode($contents, true);

        $eventData = $data['events'][0];

        $controller = new MailerLiteController();
        $controller->subscriberRemovedFromGroup($eventData);

        $subscriber = Subscriber::where('email', 'qqqqffvvgqq@init.biz')->first();
        $this->assertEquals(0, $subscriber->tags->count());
    }

    public function testSubscriberUnsubscribed()
    {
        $subscriber = new Subscriber();
        $subscriber->email = "qqqqffvvgqq@init.biz";
        $subscriber->status = Subscriber::STATUS_ACTIVE;
        $subscriber->save();

        $contents = file_get_contents(plugins_path('initbiz/newsletter/tests/fixtures/webhook-4.json'));
        $data = json_decode($contents, true);

        $eventData = $data['events'][0];

        $controller = new MailerLiteController();
        $controller->subscriberUnsubscribed($eventData);

        $subscriber = Subscriber::where('email', 'qqqqffvvgqq@init.biz')->first();
        $this->assertEquals(Subscriber::STATUS_UNSUBSCRIBED, $subscriber->status);
    }

    public function testSubscriberDeleted()
    {
        $subscriber = new Subscriber();
        $subscriber->email = "qqqqffqq@init.biz";
        $subscriber->save();

        $contents = file_get_contents(plugins_path('initbiz/newsletter/tests/fixtures/webhook-5.json'));
        $data = json_decode($contents, true);

        $eventData = $data['events'][0];

        $controller = new MailerLiteController();
        $controller->subscriberDeleted($eventData);

        $this->assertEquals(0, Subscriber::count());
    }
}
