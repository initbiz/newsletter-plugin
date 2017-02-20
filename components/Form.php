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

class Form extends ComponentBase {

    public function componentDetails() {
        return [
            'name'        => 'NewsletterForm',
            'description' => 'Newsletter Form component'
        ];
    }


    public function onRun() {
        $this->addJs('assets/js/custom-newsletter.js');
        $this->page['checkboxes'] = Checkbox::all();
        $this->page['button_text'] = Lang::get('initbiz.newsletter::lang.form.button_text');
    }


    public function onSubscription() {
        $checked =[];
        $checkboxes = Checkbox::all();
        foreach($checkboxes as $checkbox){
            isset(post($checkbox->name)[1])? array_push($checked,$checkbox->id):'';
        }

        if (!post('email')) {
            return ['status' => 'fail',
                    'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.email_cannot_be_empty')];
        }

        $validation = Validator::make(['email' => post('email')], ['email'=>'email']);
        if ($validation->fails()) {
            return ['status' => 'fail',
                    'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.email_validation_failed')];
        }
        $subscriber = new Subscriber();
        $subscriber->email = post('email');
        $subscriber->confirmed = false;
        $subscriber->token = hash('sha1', mt_rand(1,100000) . $subscriber->email);
        if ($subscriber->save() && $subscriber->checkboxes()->sync($checked)) {
            Mail::send('initbiz.newsletter::mail.subscription',
                [
                    'activationLink' => url() . '/' . Settings::get('managementpage')
                        . '/'. $subscriber->email . '/' . $subscriber->token
                ],
                function($message) use ($subscriber) {
                    $message->to($subscriber->email, "")->subject($this->title);
                });
            return ['status' => 'success',
                    'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_success')];
        } else {
            return ['status' => 'fail',
                    'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_error')];
        }


    }
}
