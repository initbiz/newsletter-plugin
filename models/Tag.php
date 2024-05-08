<?php

namespace Initbiz\Newsletter\Models;

use Model;
use Event;

/**
 * Tag Model
 */
class Tag extends Model
{
    use \October\Rain\Database\Traits\Nullable;
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string table associated with the model
     */
    public $table = 'initbiz_newsletter_tags';

    /**
     * @var array rules for validation
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required',
    ];

    /**
     * @var array nullable attribute names which should be set to null when empty.
     */
    protected $nullable = [];

    /**
     * @var array slugs are attributes to automatically generate unique URL names (slugs) for.
     */
    protected $slugs = [
        'slug' => 'name'
    ];

    /**
     * @var array dates attributes that should be mutated to dates
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public $belongsToMany = [
        'subscribers' => [
            Subscriber::class,
            'table' => 'initbiz_newsletter_subscriber_tag',
        ],
    ];

    public function afterCreate()
    {
        Event::fire('initbiz.newsletter.tagCreate', [$this]);
    }

    public function afterSave()
    {
        Event::fire('initbiz.newsletter.tagSave', [$this]);
    }

    public function beforeDelete()
    {
        Event::fire('initbiz.newsletter.tagDelete', [$this]);
    }
}
