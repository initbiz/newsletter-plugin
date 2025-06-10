<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Tests\Unit\Models;

use PluginTestCase;
use Initbiz\Newsletter\Models\Subscriber;
use October\Rain\Exception\ValidationException;

class SubscriberTest extends PluginTestCase
{
    public function testAdditionalData()
    {
        $subscriber = new Subscriber();
        $subscriber->email = "john@example.com";
        $subscriber->first_name = "John";
        $subscriber->last_name = "Doe";
        $subscriber->save();

        $subscriber->setAdditionalData('testKey', 'testValue');
        $this->assertEquals('testValue', $subscriber->getAdditionalData('testKey'));
        $subscriber->save();

        $assertedAdditionalData = [
            ['key' => 'testKey', 'value' => 'testValue']
        ];

        $additionalData = Subscriber::where('id', $subscriber->id)->select('additional_data')->first();
        $this->assertEquals($assertedAdditionalData, $additionalData->additional_data);

        $subscriber->setAdditionalData('testKey2', 'testValue2');
        $this->assertEquals('testValue2', $subscriber->getAdditionalData('testKey2'));
        $subscriber->save();

        $this->assertEquals('testValue', $subscriber->getAdditionalData('testKey'));
        $this->assertEquals('testValue2', $subscriber->getAdditionalData('testKey2'));

        $assertedAdditionalData = [
            ['key' => 'testKey', 'value' => 'testValue'],
            ['key' => 'testKey2', 'value' => 'testValue2'],
        ];

        $additionalData = Subscriber::where('id', $subscriber->id)->select('additional_data')->first();
        $this->assertEquals($assertedAdditionalData, $additionalData->additional_data);
    }

    public function testValidation()
    {
        $subscriber = new Subscriber();
        $subscriber->first_name = "John";
        $subscriber->last_name = "Doe";
        $subscriber->email = 'john@example.com';
        $subscriber->setAdditionalData('abcd', 'asdaoij@');
        $this->assertTrue($subscriber->validate());

        $subscriber->setAdditionalData('!@#asd', 'asdaoij@');
        $this->expectException(ValidationException::class);
        $subscriber->validate();
    }
}
