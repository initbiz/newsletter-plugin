<?php namespace Initbiz\Newsletter\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Subscribers as Subscriber;

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
                'title'       => 'Kod subskrybenta',
                'description' => 'Kod subskrybenta',
                'default'     => '{{ :token }}',
                'type'        => 'string'
            ],
            'mail' => [
                'title'       => 'Email subskrybenta',
                'description' => 'Email subskrybenta',
                'default'     => '{{ :mail }}',
                'type'        => 'string'
            ]
        ];
    }

    public function onRun() {

        $this->addJs('assets/js/custom-newsletter.js');
        $this->token = $this->page['token'] = $this->property('token');
        $this->mail = $this->page['mail'] = $this->property('mail');

        $this->page['subscriberExist'] = $this->checkIfExist($this->property('mail'),$this->property('token'));
        $this->page['confirmed'] = true;

        if($this->checkIfExist($this->property('mail'), $this->property('token'))) {
            if(!Subscriber::where('email', '=',$this->property('mail'))->where('token', '=', $this->property('token'))->first()->confirmed) {
                $this->activate($this->property('mail'),$this->property('token'));
            }
        }
    }

    public function onUnsubscribe() {
        return ['status' => (Subscriber::where('token', '=',post('token'))->where('email', '=', post('mail'))->delete())? 'success' : 'failed'];
    }

    public function activate($email, $token) {
        Subscriber::where('email', '=',$email)->where('token', '=', $token)->update(['confirmed' => true]);
        $this->page['confirmedBox'] = "Dziękujemy za potwierdzenie subskrybcji";
        $this->page['confirmed'] = false;
    }

    public function checkIfExist($email, $token) {
        return (count(Subscriber::where('email', '=',$email)->where('token', '=', $token)->get()))? true : false;
    }

}
