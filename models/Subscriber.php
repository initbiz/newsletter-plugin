<?php

namespace Initbiz\Newsletter\Models;

use Model;
use Initbiz\Newsletter\Models\Tag;
use Initbiz\Newsletter\Classes\Helpers;
use Initbiz\Newsletter\Models\Checkbox;

class Subscriber extends Model
{

    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_subscribers';

    protected $fillable = ['confirmed', 'email', 'token'];

    public $rules = [
        'email'   => 'required|email',
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

    public function getTokenAttribute()
    {
        if ($this->exists && $this->attributes['token']) {
            return $this->attributes['token'];
        }

        return Helpers::generateToken();
    }
}
