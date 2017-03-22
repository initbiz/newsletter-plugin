<?php
namespace Initbiz\Newsletter\Models;

use Model;
use File;
use Str;
use App;
use DB;
use Mail;

class Checkbox extends Model {

    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_checkboxes';

    protected $fillable = ['required', 'name', 'text'];

    public $rules = [
        'required'   => 'boolean',
        'name' => 'string',
        'text' => 'string'
    ];
    public $belongsToMany = [
        'subscribers' => ['Initbiz\Newsletter\Models\Subscriber', 'table' => 'initbiz_newsletter_checkbox_subscriber'],
        'messages' => ['Initbiz\Newsletter\Models\Message', 'table' => 'initbiz_newsletter_checkbox_message']
    ];

}