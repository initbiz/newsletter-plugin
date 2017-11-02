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
    protected $email;
    protected $token;
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

    protected function prepareVars()
    {
        $this->page['button_text'] = Lang::get('initbiz.newsletter::lang.manage.button_text');
        $this->page['thank_you_message'] = Lang::get('initbiz.newsletter::lang.manage.thank_you_message');
        $this->page['unsubscribe_success'] = Lang::get('initbiz.newsletter::lang.manage.unsubscribe_success');
        $this->page['unsubscribe_failed'] = Lang::get('initbiz.newsletter::lang.manage.unsubscribe_failed');
        $this->page['wrong_path'] = Lang::get('initbiz.newsletter::lang.manage.wrong_path');
        $this->page['confirmed'] = true;
        $this->token = $this->page['token'] = $this->property('token');
        $this->email = $this->page['email'] = $this->property('email');

        $this->subscriber = Subscriber::where('email', '=',$this->email)
            ->where('token', '=', $this->token)
            ->first();
    }

    public function onRun() {
        $this->addJs('assets/js/custom-newsletter.js');
        $this->prepareVars();
        $userCheckboxes = $this->getSubscriberCheckboxesName($this->subscriber);
        $notRequiredCheckboxes = $this->getAllNotRequiredCheckboxes()->toArray();
        $checkedNotRequiredCheckboxes = $this->addToCheckboxesIfChecked($notRequiredCheckboxes, $userCheckboxes);
        $this->page['checkboxes'] = $checkedNotRequiredCheckboxes;
        $this->activateSubscriber();

    }

    public function onUnsubscribe() {
        if ($this->deleteSubscriber(post('email'), post('token'))) {
            return ['status' => 'success',
                    'content' => Lang::get('initbiz.newsletter::lang.manage.unsubscribe_success'),
                    'redirectUrl' => url()];
        } else {
            return ['status' => 'fail',
                    'content' => Lang::get('initbiz.newsletter::lang.manage.unsubscribe_failed'),
                    'redirectUrl' => url()];
        }
    }

    protected function deleteSubscriber($email, $token)
    {
        $subscriber = Subscriber::where('token', $token)
            ->where('email', $email)->first();
        return ($subscriber->checkboxes()->detach() && $subscriber->delete())? true: false;
    }

    protected function activate(Subscriber $subscriber) {
        $subscriber->update(['confirmed' => true]);
        $this->page['confirmedBox'] = "initbiz.newsletter::lang.confirmedBox.message";

    }

    protected function checkIfExist(Subscriber $subscriber) {
        return (count($subscriber->get()))? true : false;
    }

    protected function getSubscriberCheckboxesName($subscriber) {
        $checkboxes = $subscriber->checkboxes()
                                 ->where('required', false)
                                 ->get()
                                 ->pluck('name')
                                 ->toArray();
        if ($checkboxes == null) {
            return [];
        } else {
            return $checkboxes;
        }
    }

    protected function addToCheckboxesIfChecked($notRequiredCheckboxes, $userCheckboxes)
    {
        foreach ($notRequiredCheckboxes as &$checkbox) {
            if (in_array( $checkbox['name'], $userCheckboxes)) {
                $checkedArray = ['checked' => true];
                $checkbox += $checkedArray;
            }
        }
        return $notRequiredCheckboxes;
    }

    protected function getAllNotRequiredCheckboxes() {
        return Checkbox::where('required', false)->get();
    }

    protected function activateSubscriber() {

        if(!empty($this->subscriber)) {
            if($this->page['subscriberExist'] = $this->checkIfExist($this->subscriber)) {
                if(!$this->subscriber->confirmed) {

                    $this->activate($this->subscriber);
                    $this->page['confirmed'] = false; //to display "Thank you for registering" message only once

                }
            }
        }
    }
    public function onUpdate()
    {
        DB::transaction(function () {
            try {
                $this->updateSubscriberCheckboxes();
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

    protected function updateSubscriberCheckboxes()
    {
        $checkboxes = $this->getAllNotRequiredCheckboxes();
        $subscriber = $this->getSubscriber(post('token'), post('email'));
        $subscriber->checkboxes()->detach();
        foreach ($checkboxes as $checkbox) {
            $checkbox = Checkbox::where('name', $checkbox->name)
                ->firstOrFail();
            if(post($checkbox->name) != null) {
                $subscriber->checkboxes()->save($checkbox);
            }
        }
    }

    public function getSubscriber($token, $email)
    {
        return Subscriber::where('token', $token)
            ->where('email', $email)
            ->firstOrFail();

    }


}
