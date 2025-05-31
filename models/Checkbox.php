<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Models;

use Model;

class Checkbox extends Model
{
    use \October\Rain\Database\Traits\Sluggable;
    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_checkboxes';

    protected $fillable = [
        'required',
        'name',
        'slug',
        'text'
    ];

    protected $slugs = [
        'slug' => 'name'
    ];

    public $rules = [
        'required' => 'required|boolean',
        'name'     => 'required|string',
        'text'     => 'required|string',
        'slug'     => 'required|string|not_in:required,name,text,slug,email'
    ];

    public $belongsToMany = [
        'subscribers' => [
            'Initbiz\Newsletter\Models\Subscriber',
            'table' => 'initbiz_newsletter_checkbox_subscriber'
        ],
        'messages' => [
            'Initbiz\Newsletter\Models\Message',
            'table' => 'initbiz_newsletter_checkbox_message'
        ]
    ];

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = ['text'];

    public function beforeDelete()
    {
        $this->subscribers()->detach();
    }

    /**
     * Scope a query to only include required Checkboxes
     */
    public function scopeRequired($query)
    {
        return $query->whereIn('required', [true, 1]);
    }

    /**
     * Scope a query to only include required Checkboxes
     */
    public function scopeNotRequired($query)
    {
        return $query->whereIn('required', [false, 0]);
    }
}
