<?php namespace Initbiz\Newsletter\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Models\Subscriber as Subscriber;
use Lang;

class NewsletterConfirm extends ComponentBase {

    protected $subscriber;
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

        $this->subscriber = Subscriber::where('email', '=',$email)->where('token', '=', $token)->first();
        $this->page['confirmed'] = true;
        $userCheckboxes = $this->getSubscriberCheckboxes($this->subscriber);
        $checkboxes = Checkbox::where('required', false)->get()->toArray();
        foreach ($checkboxes as &$checkbox) {
            if (in_array( $checkbox['name'], $userCheckboxes)) {
                $checkedArray = ['checked' => true];
                $checkbox += $checkedArray;
            }
        }

        $this->page['checkboxes'] = $checkboxes;

        if(!empty($this->subscriber)) {

            if($this->page['subscriberExist'] = $this->checkIfExist($this->subscriber)) {
                if(!$this->subscriber->confirmed) {

                    $this->activate($this->subscriber);
                    $this->page['confirmed'] = false; //to display "Thank you for registering" message only once

                }

            }
        }

    }

    public function onUnsubscribe() {
        if (Subscriber::where('token', '=',post('token'))->where('email', '=', post('email'))->delete()) {
            return ['status' => 'success', 
                    'content' => Lang::get('initbiz.newsletter::lang.manage.unsubscribe_success'),
                    'redirectUrl' => url()];
        } else {
            return ['status' => 'fail', 
                    'content' => Lang::get('initbiz.newsletter::lang.manage.unsubscribe_failed'),
                    'redirectUrl' => url()];
        }
    }

    public function activate(Subscriber $subscriber) {
        $subscriber->update(['confirmed' => true]);
        $this->page['confirmedBox'] = "initbiz.newsletter::lang.confirmedBox.message";

    }

    public function checkIfExist(Subscriber $subscriber) {
        return (count($subscriber->get()))? true : false;
    }

    protected function getSubscriberCheckboxes($subscriber) {
        $checkboxes = $subscriber->checkboxes()->where('required', false)->lists('name');
        if ($checkboxes == null) {
            return [];
        } else {
            return $checkboxes;
        }

    }

    public function onUpdate()
    {
        DB::transaction(function () {
            $checkboxes = Checkbox::where('required', false)->get();
            $subscriber = Subscriber::where('token', '=',post('token'))
                ->where('email', '=', post('email'))
                ->firstOrFail();
            try {
                $subscriber->checkboxes()->detach();

                foreach ($checkboxes as $checkbox) {
                    $checkbox = Checkbox::where('name', $checkbox->name)
                        ->firstOrFail();
                    if(post($checkbox->name) != null) {
                        $subscriber->checkboxes()->save($checkbox);
                    }
                }
            } catch (\PDOException $e) {
                return ['status' => 'fail',
                    'content' => Lang::get('initbiz.newsletter::lang.manage.update_failed'),
                    'redirectUrl' => url()];
            }

        });

        return ['status' => 'success',
            'content' => Lang::get('initbiz.newsletter::lang.manage.update_success'),
            'redirectUrl' => url()];
    }
}
