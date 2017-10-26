<?php namespace Initbiz\Newsletter\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Initbiz\Newsletter\Controllers\Checkboxes;
use Initbiz\Newsletter\Controllers\Subscribers;
use Model;
use File;
use Str;
use App;
use DB;
use Mail;
use Initbiz\Newsletter\Models\Settings;

class Message extends Model {

    use \October\Rain\Database\Traits\Validation;

    public $table = 'initbiz_newsletter_messages';

    public $rules = [
        'title'   => 'required|between:3,100',
        'content' => 'required'
    ];

    public $belongsToMany = [
        'checkboxes' => ['Initbiz\Newsletter\Models\Checkbox', 'table' => 'initbiz_newsletter_checkbox_message']
     ];

    public function beforeSave()
    {
        if ($this->sent && $this->sent != '') {
            if (Input::get('toAll') == '1') {
                $subscribers = DB::table('initbiz_newsletter_subscribers')->where("confirmed", 1)->get();
            } else {
                $inputs = new Collection(Input::get('checkboxes'));
                $subscribers = Subscriber::whereHas('checkboxes', function ($query) use ($inputs) {
                        $query->whereIn('name', $inputs->flatten());
                })->get();
            }
            foreach ($subscribers->unique('email') as $subscriber) {
                $params = [
                    'title' => $this->title,
                    'content' => $this->content,
                    'newsletterLink' => url() . '/' . Settings::get('managementpage') . '/' . $subscriber->email . '/' . $subscriber->token
                ];

                $this->email = $subscriber->email;

                Mail::send('initbiz.newsletter::mail.message', $params, function ($message) {
                    $message->to($this->email)->subject($this->title);
                });
            }
            unset($this->email, $this->name);
        }


    }
}
