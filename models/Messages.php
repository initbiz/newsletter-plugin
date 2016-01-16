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


    public function beforeValidate() {
        $invalid = DB::table('initbiz_newsletter_messages')->where('slug', '=', $this->slug)->first();
        if ($invalid) {
            throw new \ValidationException(['unique_attribute' => 'Podany slug już istnieje!']);
        }
    }

//    public function beforeSave() {
//        if (!isset($this->slug) || empty($this->slug)) {
//            $this->slug = Str::slug($this->title);
//        }
//
//        if ($this->sent && $this->sent != '') {
//            $locale = App::getLocale();
//
//            if (!File::exists('plugins/initbiz/newsletter/views/mail/email_'.$locale.'.htm')) {
//                $locale = 'en';
//            }
//
//            $subscribers = DB::table('initbiz_newsletter_subscribers')->get();
//
//            foreach ($subscribers as $subscriber) {
//                $params = [
//                    'email' => $subscriber->email
//                ];
//
//                $this->email = $subscriber->email;
//
//                Mail::send('indikator.news::mail.email_'.$locale, $params, function($message)
//                {
//                    $message->to($this->email, "Subskrybent")->subject($this->title);
//                });
//
////                DB::table('news_subscribers')->where('id', $user->id)->update(array('statistics' => ($user->statistics + 1)));
//            }
//
//            unset($this->email, $this->name);
//        }
//    }

}