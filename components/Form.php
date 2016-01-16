<?php namespace Initbiz\Newsletter\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Subscribers as Subscriber;

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
        $data = post();

        $subscriber = new Subscriber();
        $subscriber->email = post('email');
        $subscriber->confirmed = false;
        $subscriber->agreed = (isset(post('formCheck')[1]))? true: false;
        $subscriber->token = hash('sha1', mt_rand(1,100000) . post('email'));

        return ['status' =>  ($subscriber->save())? 'success' : 'fail'];
    }
}
