<?php

namespace Initbiz\Newsletter\Tests\Unit\Classes;

use PluginTestCase;
use October\Rain\Mail\FakeMailer;
use Initbiz\Newsletter\Models\Tag;
use October\Rain\Support\Facades\Mail;
use Initbiz\Newsletter\Components\Form;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Models\Subscriber;

class FormTest extends PluginTestCase
{
    public function testOnSubscription()
    {
        Mail::swap(app()->make(FakeMailer::class));

        $tag = new Tag();
        $tag->name = "Test";
        $tag->slug = "test";
        $tag->save();

        $tag = new Tag();
        $tag->name = "Test2";
        $tag->slug = "test2";
        $tag->save();

        $checkbox = new Checkbox();
        $checkbox->name = "GDPR";
        $checkbox->slug = "gdpr";
        $checkbox->required = true;
        $checkbox->text = 'You must accept GDPR';
        $checkbox->save();

        $checkbox = new Checkbox();
        $checkbox->name = "Newsletter";
        $checkbox->slug = "newsletter";
        $checkbox->required = false;
        $checkbox->text = 'Newsletter';
        $checkbox->save();

        $properties = [
            'tags' => [
                'test',
                'test2',
            ]
        ];

        $component = new Form(null, $properties);
        $data = [
            'email' => 'test@example.com',
            'gdpr' => 'on',
        ];

        $component->onSubscription($data);

        Mail::assertQueued('initbiz.newsletter::mail.subscription');
        $this->assertEquals(1, Subscriber::count());
        $subscriber = Subscriber::where('email', 'test@example.com')->first();
        $this->assertEquals($data['email'], $subscriber->email);
        $this->assertEquals(1, $subscriber->checkboxes()->count());
        $this->assertEquals(false, $subscriber->confirmed);
        $this->assertNotEmpty($subscriber->token);
        $this->assertEquals(2, $subscriber->tags()->count());

        $data = [
            'email' => 'test2@example.com',
            'gdpr' => 'on',
            'newsletter' => 'on',
        ];

        $component->onSubscription($data);

        $this->assertEquals(2, Subscriber::count());
        $subscriber = Subscriber::where('email', 'test2@example.com')->first();
        $this->assertEquals($data['email'], $subscriber->email);
        $this->assertEquals(2, $subscriber->checkboxes()->count());

        $data = [
            'email' => 'test2@example.com',
            'gdpr' => 'on',
        ];

        $component->onSubscription($data);

        $this->assertEquals(2, Subscriber::count());
        $subscriber = Subscriber::where('email', 'test2@example.com')->first();
        $this->assertEquals($data['email'], $subscriber->email);
        $this->assertEquals(2, $subscriber->checkboxes()->count());
    }
}
