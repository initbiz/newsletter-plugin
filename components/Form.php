<?php namespace Initbiz\Newsletter\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Subscribers as Subscriber;
use Initbiz\Newsletter\Models\Settings;
use Mail;

class Form extends ComponentBase {

    public function componentDetails() {
        return [
            'name'        => 'NewsletterForm',
            'description' => 'Newsletter Form component'
        ];
    }

    public function onRun() {
        $this->addJs('assets/js/custom-newsletter.js');
    }


    public function onSubscription() {
        $hash = hash('sha1', mt_rand(1,100000) . post('email'));
        $subscriber = new Subscriber();
        $subscriber->email = post('email');
        $subscriber->confirmed = false;
        $subscriber->agreed = (isset(post('formCheck')[1]))? true: false;
        $subscriber->token = $hash;

        Mail::send('initbiz.newsletter::mail.subscription',
            [
                'activationLink' => url() . '/' . Settings::get('managementpage') . '/'.post('email') . '/' .$hash
            ],
            function($message)
        {
            $message->to(post('email'), "Subskrybent")->subject('Potwierdź subskrypcję');
        });

        return ['status' =>  ($subscriber->save())? 'success' : 'fail'];
    }
}
