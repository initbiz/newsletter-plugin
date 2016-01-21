<?php namespace Initbiz\Newsletter\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Subscribers as Subscriber;
use Lang;

class NewsletterConfirm extends ComponentBase {

    public function componentDetails()
    {
        return [
            'name'        => 'NewsletterConfirm',
            'description' => 'NewsletterConfirm'
        ];
    }

    public function defineProperties()
    {
        return [
            'token' => [
                'title'       => 'initbiz.newsletter::lang.token.title',
                'description' => 'initbiz.newsletter::lang.token.description',
                'default'     => '{{ :token }}',
                'type'        => 'string'
            ],
            'email' => [
                'title'       => 'initbiz.newsletter::lang.email.title',
                'description' => 'initbiz.newsletter::lang.email.description',
                'default'     => '{{ :email }}',
                'type'        => 'string'
            ]
        ];
    }

    public function onRun() {

        $this->addJs('assets/js/custom-newsletter.js');
        $this->page['button_text'] = Lang::get('initbiz.newsletter::lang.manage.button_text');
        $this->page['thank_you_message'] = Lang::get('initbiz.newsletter::lang.manage.thank_you_message');
        $this->page['unsubscribe_success'] = Lang::get('initbiz.newsletter::lang.manage.unsubscribe_success');
        $this->page['unsubscribe_failed'] = Lang::get('initbiz.newsletter::lang.manage.unsubscribe_failed');
        $this->page['wrong_path'] = Lang::get('initbiz.newsletter::lang.manage.wrong_path');

        $token = $this->page['token'] = $this->property('token');
        $email = $this->page['email'] = $this->property('email');

        $subscriber = Subscriber::where('email', '=',$email)->where('token', '=', $token)->first();
        $this->page['confirmed'] = true;

        if(!empty($subscriber)) {

            if($this->page['subscriberExist'] = $this->checkIfExist($subscriber)) {
                if(!$subscriber->confirmed) {

                    $this->activate($subscriber);
                    $this->page['confirmed'] = false; //to display "Thank you for registering" message only once
                }
            }
        }
    }

    public function onUnsubscribe() {
        return ['status' => (Subscriber::where('token', '=',post('token'))->where('email', '=', post('email'))->delete())? 'success' : 'failed', 'redirectUrl' => url()];
    }

    public function activate(Subscriber $subscriber) {
        $subscriber->update(['confirmed' => true]);
        $this->page['confirmedBox'] = "initbiz.newsletter::lang.confirmedBox.message";

    }

    public function checkIfExist(Subscriber $subscriber) {
        return (count($subscriber->get()))? true : false;
    }

}
