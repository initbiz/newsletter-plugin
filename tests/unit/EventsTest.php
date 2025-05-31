<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Tests\Unit;

use Event;
use PluginTestCase;
use Initbiz\Newsletter\Models\Tag;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Models\Subscriber;

class EventsTest extends PluginTestCase
{
    public function testEventsAreDispatched()
    {
        Event::fake([
            "initbiz.newsletter.subscriberCreate",
            "initbiz.newsletter.subscriberDelete",
            "initbiz.newsletter.tagCreate",
            "initbiz.newsletter.tagDelete",
            "initbiz.newsletter.subscriberCheckboxesAttached",
            "initbiz.newsletter.subscriberCheckboxesDetached",
            "initbiz.newsletter.subscriberTagsAttached",
            "initbiz.newsletter.subscriberTagsDetached",
        ]);

        $tag = new Tag();
        $tag->name = "Test";
        $tag->slug = "test";
        $tag->save();

        Event::assertDispatched('initbiz.newsletter.tagCreate');

        $checkbox = new Checkbox();
        $checkbox->name = "GDPR";
        $checkbox->slug = "gdpr";
        $checkbox->required = true;
        $checkbox->text = 'You must accept GDPR';
        $checkbox->save();

        $subscriber = new Subscriber();
        $subscriber->email = 'test@example.com';
        $subscriber->save();

        Event::assertDispatched('initbiz.newsletter.subscriberCreate');

        $subscriber->attachTags($tag);
        Event::assertDispatched('initbiz.newsletter.subscriberTagsAttached');

        $subscriber->tags()->detach($tag->id);
        Event::assertDispatched('initbiz.newsletter.subscriberTagsDetached');

        $tag->delete();
        Event::assertDispatched('initbiz.newsletter.tagDelete');

        $subscriber->delete();
        Event::assertDispatched('initbiz.newsletter.subscriberDelete');
    }
}
