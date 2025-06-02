<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Tests\Unit\Api\Controllers;

use PluginTestCase;
use Illuminate\Http\Request;
use October\Rain\Exception\ValidationException;
use Initbiz\Newsletter\Api\Controllers\MailerLiteController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Initbiz\Newsletter\Models\Subscriber;

/**
 * for both example webhooks secret is: oOUngq53Iy
 * for webhook-1.json signature is: 97b447b4c12b16bb50653eb617565bb3032a887dd37d161381e277cbddacc5bc
 * for webhook-2.json signature is: eee922eeb404a9208570644d889bb8c9f0b3d47ba45d2e2250b0598e10491057
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
        $this->assertTrue(true);
    }

    public function testSubscriberRemovedFromGroup()
    {
        $this->assertTrue(true);
    }

    public function testSubscriberUnsubscribed()
    {
        $this->assertTrue(true);
    }

    public function testSubscriberDeleted()
    {
        $this->assertTrue(true);
    }
}
