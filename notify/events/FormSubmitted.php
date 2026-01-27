<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Notify\Events;

use Lang;
use RainLab\Notify\Classes\EventBase;

class FormSubmitted extends EventBase
{
    public $conditions = [
        \Initbiz\Newsletter\Notify\Conditions\ParticularRef::class,
        \Initbiz\Newsletter\Notify\Conditions\ParticularTag::class,
    ];

    /**
     * Returns information about this event, including name and description.
     */
    public function eventDetails()
    {
        return [
            'name' => Lang::get('initbiz.newsletter::lang.form_submitted_event.name'),
            'description' => Lang::get('initbiz.newsletter::lang.form_submitted_event.description'),
            'group' => 'newsletter',
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
            'subscriber' => [
                'title' => Lang::get('initbiz.newsletter::lang.form_submitted_event.subscriber'),
                'label' => Lang::get('initbiz.newsletter::lang.form_submitted_event.subscriber'),
            ],
            'checked_checkboxes' => [
                'title' => Lang::get('initbiz.newsletter::lang.form_submitted_event.checked_checkboxes'),
                'label' => Lang::get('initbiz.newsletter::lang.form_submitted_event.checked_checkboxes'),
            ],
            'tags' => [
                'title' => Lang::get('initbiz.newsletter::lang.form_submitted_event.tags'),
                'label' => Lang::get('initbiz.newsletter::lang.form_submitted_event.tags'),
            ],
        ];
    }

    public static function makeParamsFromEvent(array $args, $eventName = null)
    {
        return [
            'ref' => array_get($args, 1),
            'post_data' => array_get($args, 2),
            'url' => array_get($args, 3),
            'subscriber' => array_get($args, 4),
            'checked_checkboxes' => array_get($args, 5),
            'tags' => array_get($args, 6),
        ];
    }
}
