<?php namespace Initbiz\Newsletter\Models;

use Model;
use File;
use Str;
use App;
use DB;
use Mail;

class Messages extends Model {

    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_messages';

    public $primaryKey = 'slug';

    public $rules = [
        'title'   => 'required|between:3,100',
        'slug'    => 'required|between:3,64',
        'content' => 'required'
    ];

}