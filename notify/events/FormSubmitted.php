<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Notify\Events;

use Lang;
use RainLab\Notify\Classes\EventBase;

class FormSubmitted extends EventBase
{
    public $conditions = [
        \Initbiz\Newsletter\Notify\Conditions\ParticularRef::class,
    ];

    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name' => Lang::get('initbiz.newsletter::lang.form_submitted_event.name'),
            'description' => Lang::get('initbiz.newsletter::lang.form_submitted_event.description'),
            'group' => Lang::get('initbiz.newsletter::lang.events_group.name'),
        ];
    }

    public function defineParams()
    {
        return [
            'ref' => [
                'title' => Lang::get('initbiz.newsletter::lang.form_submitted_event.ref'),
                'label' => Lang::get('initbiz.newsletter::lang.form_submitted_event.ref'),
            ],
            'post_data' => [
                'title' => Lang::get('initbiz.newsletter::lang.form_submitted_event.post_data'),
                'label' => Lang::get('initbiz.newsletter::lang.form_submitted_event.post_data'),
            ],
            'url' => [
                'title' => Lang::get('initbiz.newsletter::lang.form_submitted_event.url'),
                'label' => Lang::get('initbiz.newsletter::lang.form_submitted_event.url'),
            ],
        ];
    }

    public static function makeParamsFromEvent(array $args, $eventName = null)
    {
        $ref =  array_get($args, 0);
        $postData =  array_get($args, 1);
        $url =  array_get($args, 2);

        return [
            'ref' => $ref,
            'post_data' => $postData,
            'url' => $url,
        ];
    }
}
