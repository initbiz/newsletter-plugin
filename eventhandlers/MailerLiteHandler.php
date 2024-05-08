<?php

namespace Initbiz\Newsletter\EventHandlers;

use Initbiz\Newsletter\Models\Settings;

class MailerLiteHandler
{
    public function subscribe($event)
    {
        if (Settings::get('enable_mailerlite_integration', false)) {
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
