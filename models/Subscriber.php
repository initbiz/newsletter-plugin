<?php

namespace Initbiz\Newsletter\Models;

use Model;
use Event;
use Initbiz\Newsletter\Models\Tag;
use Initbiz\Newsletter\Classes\Helpers;
use Initbiz\Newsletter\Models\Checkbox;
use October\Rain\Database\Collection;

class Subscriber extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_subscribers';

    protected $fillable = [
        'email',
        'address_line1',
        'address_line2',
        'company',
        'sex',
        'age',
        'phone',
        'city',
        'zip',
        'date_of_birth',
        'additional_fields',
    ];

    public $attributes = [
        'confirmed' => false,
    ];

    public $rules = [
        'email' => 'required|email|between:6,255|unique:initbiz_newsletter_subscribers'
    ];

    public $belongsToMany = [
        'checkboxes' => [
            Checkbox::class,
            'table' => 'initbiz_newsletter_checkbox_subscriber',
        ],

        'tags' => [
            Tag::class,
            'table' => 'initbiz_newsletter_subscriber_tag',
        ]
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct();

        $this->bindEvent('model.relation.afterAttach', function (string $relationName, array $ids) {
            if ($relationName === "checkboxes") {
                $checkboxes = Checkbox::whereIn('id', $ids)->get();
                Event::fire('initbiz.newsletter.subscriberCheckboxesAttached', [$this, $checkboxes]);
            } elseif ($relationName === "tags") {
                $tags = Tag::whereIn('id', $ids)->get();
                Event::fire('initbiz.newsletter.subscriberTagsAttached', [$this, $tags]);
            }
        });

        $this->bindEvent('model.relation.afterDetach', function (string $relationName, array $ids) {
            if ($relationName === "checkboxes") {
                $checkboxes = Checkbox::whereIn('id', $ids)->get();
                Event::fire('initbiz.newsletter.subscriberCheckboxesDetached', [$this, $checkboxes]);
            } elseif ($relationName === "tags") {
                $tags = Tag::whereIn('id', $ids)->get();
                Event::fire('initbiz.newsletter.subscriberTagsDetached', [$this, $tags]);
            }
        });
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function beforeCreate()
    {
        if (empty($this->token)) {
            $this->token = Helpers::generateToken();
        }
    }

    public function afterCreate()
    {
        Event::fire('initbiz.newsletter.subscriberCreate', [$this]);
    }

    public function afterSave()
    {
        Event::fire('initbiz.newsletter.subscriberSave', [$this]);
    }

    public function beforeDelete()
    {
        Event::fire('initbiz.newsletter.subscriberDelete', [$this]);
    }

    public function attachCheckboxes(Collection $checkboxes): void
    {
        $alreadyCheckedByUser = $this->checkboxes->pluck('id')->toArray();
        $yetUncheckedCheckboxesIds = array_diff($checkboxes->pluck('id')->toArray(), $alreadyCheckedByUser);
        $this->checkboxes()->attach($yetUncheckedCheckboxesIds);
    }

    public function attachTags(Collection $tags): void
    {
        $alreadyInUser = $this->tags->pluck('id')->toArray();
        $yetNotInUserIds = array_diff($tags->pluck('id')->toArray(), $alreadyInUser);
        $this->tags()->attach($yetNotInUserIds);
    }
}
