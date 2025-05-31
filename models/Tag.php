<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Models;

use Event;
use Model;

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
        'additional_data.*.key' => 'nullable|alpha_dash:ascii|max:250',
        'additional_data.*.value' => 'nullable|max:250',
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

    protected $jsonable = ['additional_data'];

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

    public function afterUpdate()
    {
        Event::fire('initbiz.newsletter.tagUpdate', [$this]);
    }

    public function beforeDelete()
    {
        Event::fire('initbiz.newsletter.tagDelete', [$this]);
    }

    /**
     * Shorthand to set values to additional_data
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setAdditionalData(string $key, string $value): void
    {
        $additionalData = $this->additional_data;
        if (!is_array($additionalData)) {
            $additionalData = [];
        }

        $found = false;
        $newAdditionalData = [];
        foreach ($additionalData as $additionalDataEntry) {
            if ($additionalDataEntry['key'] === $key) {
                $additionalDataEntry['value'] = $value;
                $found = true;
            }
            $newAdditionalData[] = $additionalDataEntry;
        }

        if (!$found) {
            $newAdditionalData[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        $this->additional_data = $newAdditionalData;
    }

    /**
     * Shorthand to get values from additional_data
     *
     * @param string $key
     * @return string|null
     */
    public function getAdditionalData(string $key): ?string
    {
        $additionalData = $this->additional_data;
        if (!is_array($additionalData)) {
            $additionalData = [];
        }

        foreach ($additionalData as $additionalDataEntry) {
            if ($additionalDataEntry['key'] === $key) {
                return $additionalDataEntry['value'];
            }
        }

        return null;
    }
}
