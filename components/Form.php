<?php namespace Initbiz\Newsletter\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Subscribers as Subscriber;
use Initbiz\Newsletter\Models\Settings;
use Illuminate\Validation\Validator as Validator;
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
        $this->page['required_checkbox'] = Settings::get('required_checkbox');
        $this->page['optional_checkbox'] = Settings::get('optional_checkbox');
        $this->page['button_text'] = Lang::get('initbiz.newsletter::lang.form.button_text');
        $this->page['sign_up_thanks'] = Lang::get('initbiz.newsletter::lang.form.sign_up_thanks');
        $this->page['sign_up_error'] = Lang::get('initbiz.newsletter::lang.form.sign_up_error');

    }


    public function onSubscription() {
        $subscriber = new Subscriber();
        $subscriber->email = post('email');
        $subscriber->confirmed = false;
        $subscriber->agreed = (isset(post('formCheck')[1]))? true: false;
        $subscriber->token = hash('sha1', mt_rand(1,100000) . $subscriber->email);
        if ($subscriber->save()) {
            Mail::send('initbiz.newsletter::mail.subscription',
                [
                    'activationLink' => url() . '/' . Settings::get('managementpage') . '/'. $subscriber->email . '/' . $subscriber->token
                ],
                function($message) use ($subscriber) {
                    $message->to($subscriber->email, "")->subject($this->title);
                });
            return ['status' => 'success'];
        } else {
            return ['status' => 'fail'];
        }


    }
}
