<?php namespace Initbiz\Newsletter\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Initbiz\Newsletter\Controllers\Checkboxes;
use Initbiz\Newsletter\Controllers\Subscribers;
use Model;
use File;
use Str;
use App;
use DB;
use Mail;
use Initbiz\Newsletter\Models\Settings;

class Message extends Model
{
    use \October\Rain\Database\Traits\Validation;

    protected $checkedCheckboxes;

    public $table = 'initbiz_newsletter_messages';

    public $rules = [
        'title'   => 'required|between:3,100',
        'content' => 'required'
    ];

    public $belongsToMany = [
        'checkboxes' => [
            'Initbiz\Newsletter\Models\Checkbox', 'table' => 'initbiz_newsletter_checkbox_message'
        ]
    ];

    //Getters

    public function getEmailTemplateOptions()
    {
        return \System\Models\MailTemplate::listAllTemplates();
    }

    public function getSendToOptions()
    {
        $options =[
            'all' => Lang::get('initbiz.newsletter::lang.messages.send_to_all')
        ];
        if (Checkbox::where('required', false)->get()->count() != 0) {
            $options += ['customized' => Lang::get('initbiz.newsletter::lang.messages.send_to_agreed')];
        }
        return $options;
    }

    /**
     * Send messages to recipients if 'sent' checkbox is checked
     * @return void
     */
    public function beforeSave()
    {
        $this->checkedCheckboxes = collect(Input::get('checkboxes'));
        if ($this->sentCheckboxChecked()) {
            $recipientsList = $this->getRecipientsList($this->checkedCheckboxes->flatten());
            $this->sendMessageToRecipients($recipientsList, $this->email_template);
        }
    }

    /**
     * Save which message was sent to which checkboxes
     * @return void
     */
    public function afterSave()
    {
        $messageCheckboxesId = Checkbox::whereIn('name', $this->checkedCheckboxes->flatten())
            ->get()
            ->pluck('id')
            ->toArray();
        $this->checkboxes()->sync($messageCheckboxesId);
    }

    public function beforeDelete()
    {
        $this->checkboxes()->detach();
    }


    protected function sentCheckboxChecked()
    {
        return ($this->sent && $this->sent != '') ? true: false;
    }

    protected function getRecipientsList($checkedCheckboxes)
    {
        $subscribers = [];
        if ($this->send_to == 'all') {
            $subscribers = Subscriber::where('confirmed', 1)->get();
        } else {
            $subscribers = Subscriber::where('confirmed', 1)
                    ->whereHas('checkboxes', function ($query) use ($checkedCheckboxes) {
                        $query->whereIn('name', $checkedCheckboxes);
                    })->get();
        }
        return $subscribers;
    }

    /**
     * Send messages to all recipients
     * @param  Collection   $recipientsList recipients list
     * @param  string       $template       teplate unique identifier to sent e-mail
     * @return void
     */
    public function sendMessageToRecipients($recipientsList, $template = 'initbiz.newsletter::mail.message')
    {
        //TODO: Create a worker or other non blocking code
        foreach ($recipientsList->unique('email') as $subscriber) {
            $params = [
                'title' => $this->title,
                'content' => $this->content,
                'newsletterLink' => url('/') . '/' . Settings::get('managementpage') . '/' . $subscriber->email . '/' . $subscriber->token
            ];

            $email = $subscriber->email;

            Mail::send($template, $params, function ($message) use ($email) {
                $message->to($email)->subject($this->title);
            });
        }
    }
}
