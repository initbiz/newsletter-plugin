<?php namespace Initbiz\Newsletter\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Input;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Models\Subscriber as Subscriber;
use Initbiz\Newsletter\Models\Settings;
use Validator;
use Mail;
use Lang;
use Db;

class Form extends ComponentBase
{
    protected $subscriber;

    public function componentDetails()
    {
        return [
            'name'        => 'NewsletterForm',
            'description' => 'Newsletter Form component'
        ];
    }

    public function prepareVars()
    {
        $this->page['checkboxes'] = Checkbox::all();
    }

    public function onRun()
    {
        $this->addJs('assets/js/custom-newsletter.js');
        $this->prepareVars();
    }


    public function onSubscription()
    {
        Db::transaction(function () {
            $checkedCheckboxes = $this->getCheckedCheckboxesId(post());

            if (!post('email')) {
                return ['status' => 'fail',
                        'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.email_cannot_be_empty')];
            }

            if (Subscriber::where('email', post('email'))->first()) {
                return ['status' => 'fail',
                        'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.email_already_exist')];
            }

            $validation = Validator::make(['email' => post('email')], ['email'=>'email']);
            if ($validation->fails()) {
                return ['status' => 'fail',
                        'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.email_validation_failed')];
            }

            // check if all reqired checkboxes are checked
            $requiredCheckboxes = Checkbox::where('required', true)->where('required', 1)->get();
            foreach ($requiredCheckboxes as $requiredCheckbox) {
                if (!in_array($requiredCheckbox->id, $checkedCheckboxes)) {
                    return ['status' => 'fail',
                            'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.checkbox_validation_failed')];
                }
            }

            if ($this->createSubscriberWithCheckboxes(post('email'), $checkedCheckboxes)) {
                $this->sendActivationEmailToSubscriber();
                return ['status' => 'success',
                        'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_success')];
            } else {
                return ['status' => 'fail',
                        'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_error')];
            }
        });
    }

    protected function getCheckedCheckboxesId($post)
    {
        $checked =[];
        $checkboxes = Checkbox::all();
        foreach ($checkboxes as $checkbox) {
            isset($post[$checkbox->name][1])? array_push($checked, $checkbox->id):'';
        }
        return $checked;
    }

    protected function createSubscriberWithCheckboxes($email, $checked)
    {
        $this->subscriber = new Subscriber();
        $this->subscriber->email = $email;
        $this->subscriber->confirmed = false;
        $this->subscriber->token = hash('sha1', mt_rand(1, 100000) . $this->subscriber->email);
        return ($this->subscriber->save() && $this->subscriber->checkboxes()->sync($checked))? true: false;
    }

    public function sendActivationEmailToSubscriber()
    {
        $subscriberEmail  = $this->subscriber->email;

        Mail::send(
            'initbiz.newsletter::mail.subscription',
            [
                'activationLink' => url('/') . ('/') . Settings::get('managementpage')
                    . '/'. $this->subscriber->email . '/' . $this->subscriber->token
            ],
            function ($message) use ($subscriberEmail) {
                $message->to($this->subscriber->email, "")->subject($this->title);
            }
        );
    }
}
