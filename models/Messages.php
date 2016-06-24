<?php namespace Initbiz\Newsletter\Models;

use Model;
use File;
use Str;
use App;
use DB;
use Mail;
use Initbiz\Newsletter\Models\Settings;

class Messages extends Model {

    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_messages';

    public $rules = [
        'title'   => 'required|between:3,100',
        'content' => 'required'
    ];

    public function beforeSave() {

        if ($this->sent && $this->sent != '') {

            $subscribers = DB::table('initbiz_newsletter_subscribers')->where("confirmed", 1)->get();

            foreach ($subscribers as $subscriber) {
                $params = [
                    'title' => $this->title,
                    'content' => $this->content,
                    'newsletterLink' =>  url() . '/' . Settings::get('managementpage') . '/'. $subscriber->email. '/' . $subscriber->token
                ];

                $this->email = $subscriber->email;

                Mail::send('initbiz.newsletter::mail.message', $params, function($message)
                {
                    $message->to($this->email, "Subskrybent")->subject($this->title);
                });
            }

            unset($this->email, $this->name);
        }
    }

}
