<?php
namespace Initbiz\Newsletter\Models;

use Model;
use File;
use Str;
use App;
use DB;
use Mail;

class Checkboxes extends Model {

    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_checkboxes';

    protected $fillable = ['required', 'name', 'text'];

    public $rules = [
        'required'   => 'required|boolean',
        'name' => 'required|string',
        'text' => 'required|string'
    ];
    public $belongsToMany = [
        'subscribers' => 'Initbiz\Newsletter\Models\Subscribers'
    ];

}