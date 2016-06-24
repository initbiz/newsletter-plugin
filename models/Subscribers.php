<?php namespace Initbiz\Newsletter\Models;

use Model;
use File;
use Str;
use App;
use DB;
use Mail;

class Subscribers extends Model {

    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_subscribers';

    protected $fillable = ['confirmed'];

    public $customMessages = ['email.required' => 'E-mail field is required']; //TODO: Translatable

    public $rules = [
        'email'   => 'required|email',
    ];

}
