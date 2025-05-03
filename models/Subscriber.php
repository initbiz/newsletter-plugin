<?php

namespace Initbiz\Newsletter\Models;

use Model;
use Event;
use Validator;
use Initbiz\Newsletter\Models\Tag;
use October\Rain\Database\Collection;
use Initbiz\Newsletter\Classes\Helpers;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Models\Settings;
use October\Rain\Exception\ValidationException;

class Subscriber extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_subscribers';

    protected $fillable = [
        'first_name',
        'last_name',
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
    ];

    public $attributes = [
        'confirmed' => false,
    ];

    public $rules = [
        'email' => 'required|email|between:6,255|unique:initbiz_newsletter_subscribers',
        'address_line1' => 'nullable|max:250',
        'address_line2' => 'nullable|max:250',
        'company' => 'nullable|max:250',
        'sex' => 'nullable|in:male,female,other',
        'age' => 'nullable|integer',
        'phone' => 'nullable|max:250',
        'city' => 'nullable|max:250',
        'zip' => 'nullable|max:250',
        'date_of_birth' => 'nullable|date|before:tomorrow',
        'additional_fields.*.key' => 'nullable|alpha_dash:ascii|max:250',
        'additional_data.*.key' => 'nullable|alpha_dash:ascii|max:250',
        'additional_fields.*.value' => 'nullable|max:250',
        'additional_data.*.value' => 'nullable|max:250',
    ];

    protected $jsonable = [
        'additional_fields',
        'additional_data',
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

        /**
         * Binding to relation.attach to fire our own events for easier extension
         */
        $this->bindEvent('model.relation.attach', function (string $relationName, array $ids) {
            if ($relationName === "checkboxes") {
                $checkboxes = Checkbox::whereIn('id', $ids)->get();
                Event::fire('initbiz.newsletter.subscriberCheckboxesAttached', [$this, $checkboxes]);
            } elseif ($relationName === "tags") {
                $tags = Tag::whereIn('id', $ids)->get();
                Event::fire('initbiz.newsletter.subscriberTagsAttached', [$this, $tags]);
            }
        });

        /**
         * Binding to relation.detach to fire our own events for easier extension
         */
        $this->bindEvent('model.relation.detach', function (string $relationName, ?array $ids) {
            // When deleting the subscriber, the event is dispatched, we want to prevent that
            if (is_null($ids)) {
                return;
            }

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

    public function afterUpdate()
    {
        Event::fire('initbiz.newsletter.subscriberUpdate', [$this]);
    }

    public function beforeDelete()
    {
        Event::fire('initbiz.newsletter.subscriberDelete', [$this]);
    }

    /**
     * Attach checkboxes to the subscriber
     *
     * @param Collection|Checkbox $checkboxes
     * @return void
     */
    public function attachCheckboxes(Collection|Checkbox $checkboxes): void
    {
        if ($checkboxes instanceof Collection && $checkboxes->isEmpty()) {
            return;
        }

        $checkboxesIds = [];
        if ($checkboxes instanceof Collection) {
            $checkboxesIds = $checkboxes->pluck('id')->toArray();
        } else {
            $checkboxesIds[] = $checkboxes->id;
        }

        $alreadyCheckedByUser = $this->checkboxes->pluck('id')->toArray();
        $yetUncheckedCheckboxesIds = array_diff($checkboxesIds, $alreadyCheckedByUser);
        $this->checkboxes()->attach($yetUncheckedCheckboxesIds);
    }

    /**
     * Attach tags to the subscriber
     *
     * @param Collection|Tag $tags
     * @return void
     */
    public function attachTags(Collection|Tag $tags): void
    {
        if ($tags instanceof Collection && $tags->isEmpty()) {
            return;
        }

        $tagsIds = [];
        if ($tags instanceof Collection) {
            $tagsIds = $tags->pluck('id')->toArray();
        } else {
            $tagsIds[] = $tags->id;
        }

        $alreadyInUser = $this->tags->pluck('id')->toArray();
        $yetNotInUserIds = array_diff($tagsIds, $alreadyInUser);
        $this->tags()->attach($yetNotInUserIds);
    }

    /**
     * Activate subscriber
     *
     * @return void
     */
    public function activate(): void
    {
        $this->confirmed = true;
        $this->save();
    }

    /**
     * Shorthand to set value to additional_fields
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function setAdditionalField(string $key, string $value): void
    {
        $additionalFields = $this->additional_fields;
        if (!is_array($additionalFields)) {
            $additionalFields = [];
        }

        /**
         * @var Settings
         */
        $settings = Settings::instance();
        $additionalFields = $settings->additional_fields;
        $validationRule = '';
        if (!empty($additionalFields)) {
            foreach ($additionalFields as $additionalFieldDef) {
                if ($additionalFieldDef['attribute'] === $key) {
                    $validationRule = $additionalFieldDef['rules'];
                    break;
                }
            }
        }

        // Backwards compatibility will let it save even if the rule is not defined
        if (!empty($validationRule)) {
            $validator = Validator::make([$key => $value], [$key => $validationRule]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }

        $found = false;
        $newAdditionalFields = [];
        foreach ($additionalFields as $additionalField) {
            if ($additionalField['key'] === $key) {
                $found = true;
            }
            $newAdditionalFields[] = $additionalField;
        }

        if (!$found) {
            $newAdditionalFields[] = [
                'key' => $key,
                'value' => $value,
            ];
        }

        $this->additional_fields = $newAdditionalFields;
    }

    /**
     * Shorthand to get value from additional_fields
     *
     * @param string $key
     * @return string|null
     */
    public function getAdditionalField(string $key): ?string
    {
        $additionalFields = $this->additional_fields;
        if (!is_array($additionalFields)) {
            $additionalFields = [];
        }

        foreach ($additionalFields as $additionalField) {
            if ($additionalField['key'] === $key) {
                return $additionalField['value'];
            }
        }

        return null;
    }

    /**
     * Shorthand to set values in additional_data
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

    /**
     * Getting additional_data in key=>value format
     *
     * @return array
     */
    public function getAdditionalDataKeyValue(): array
    {
        $additionalData = $this->additional_data;
        if (!is_array($additionalData)) {
            $additionalData = [];
        }

        $parsed = [];

        foreach ($additionalData as $additionalDataEntry) {
            $parsed[$additionalDataEntry['key']] = $additionalDataEntry['value'];
        }

        return $parsed;
    }

    /**
     * Get all attributes of the subscriber that can be set from frontend form
     *
     * @return array
     */
    public static function getFillableAttributes(): array
    {
        $rules = (new self())->rules;

        $fillableAttributes = [
            'email' => [
                'label' => 'initbiz.newsletter::lang.subscriber.email',
                'type' => 'email',
                'input_placeholder' => 'initbiz.newsletter::lang.form.placeholder_email',
                'rules' => $rules['email'],
            ],
            'first_name' => [
                'label' => 'initbiz.newsletter::lang.subscriber.first_name',
                'type' => 'text',
                'rules' => $rules['first_name'],
            ],
            'last_name' => [
                'label' => 'initbiz.newsletter::lang.subscriber.last_name',
                'type' => 'text',
                'rules' => $rules['last_name'],
            ],
            'sex' => [
                'label' => 'initbiz.newsletter::lang.subscriber.sex',
                'type' => 'text',
                'rules' => $rules['sex'],
            ],
            'address_line1' => [
                'label' => 'initbiz.newsletter::lang.subscriber.address_line1',
                'type' => 'text',
                'rules' => $rules['address_line1'],
            ],
            'address_line2' => [
                'label' => 'initbiz.newsletter::lang.subscriber.address_line2',
                'type' => 'text',
                'rules' => $rules['address_line2'],
            ],
            'company' => [
                'label' => 'initbiz.newsletter::lang.subscriber.company',
                'type' => 'text',
                'rules' => $rules['company'],
            ],
            'phone' => [
                'label' => 'initbiz.newsletter::lang.subscriber.phone',
                'type' => 'text',
                'rules' => $rules['phone'],
            ],
            'zip' => [
                'label' => 'initbiz.newsletter::lang.subscriber.zip',
                'type' => 'text',
                'rules' => $rules['zip'],
            ],
            'city' => [
                'label' => 'initbiz.newsletter::lang.subscriber.city',
                'type' => 'text',
                'rules' => $rules['city'],
            ],
            'date_of_birth' => [
                'label' => 'initbiz.newsletter::lang.subscriber.date_of_birth',
                'type' => 'text',
                'rules' => $rules['date_of_birth'],
            ],
            'age' => [
                'label' => 'initbiz.newsletter::lang.subscriber.age',
                'type' => 'text',
                'rules' => $rules['age'],
            ],
        ];

        /**
         * @var Settings
         */
        $settings = Settings::instance();
        $additionalFields = $settings->additional_fields;
        if (!empty($additionalFields)) {
            foreach ($additionalFields as $additionalFieldDef) {
                $attribute = 'additional_fields[' . $additionalFieldDef['attribute'] . ']';
                $fillableAttributes[$attribute] = [
                    'label' => $additionalFieldDef['label'],
                    'type' => $additionalFieldDef['type'],
                    'rules' => $additionalFieldDef['rules'],
                    'input_placeholder' => $additionalFieldDef['input_placeholder'],
                ];
            }
        }

        Event::fire('initbiz.newsletter.extendFillableAttributes', [&$fillableAttributes]);

        return $fillableAttributes;
    }
}
