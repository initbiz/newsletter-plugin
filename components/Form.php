<?php namespace Initbiz\Newsletter\Components;

use Db;
use Mail;
use Lang;
use Validator;
use Exception;
use Cms\Classes\Page;
use ValidationException;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Input;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Models\Settings;
use October\Rain\Exception\AjaxException;
use Initbiz\Newsletter\Classes\SubscribtionException;
use Initbiz\Newsletter\Models\Subscriber as Subscriber;

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
        $this->prepareVars();
    }


    public function onSubscription()
    {
        $result;
            Db::transaction(function () use(&$result) {
            try {
                $data = post();

                $rules = [
                    'email'    => 'required|email|between:6,255|unique:initbiz_newsletter_subscribers'
                ];
                $validation = Validator::make($data, $rules);
                if ($validation->fails()) {
                    throw new ValidationException($validation);
                }
                // check if all reqired checkboxes are checked
                $requiredCheckboxes = Checkbox::required()->get();
                $checkedCheckboxes = $this->getCheckedCheckboxesId(post());
                foreach ($requiredCheckboxes as $requiredCheckbox) {
                    if (!in_array($requiredCheckbox->id, $checkedCheckboxes)) {
                        throw new ValidationException(['requiredCheckboxes' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.checkbox_validation_failed')]);
                    }
                }
            } catch (Exception $e) {
                throw $e;
            }
            try {
                $this->createSubscriberWithCheckboxes(post('email'), $checkedCheckboxes);
                $this->sendActivationEmailToSubscriber();
                $result = ['content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_success')];
            } catch (Exception $e) {
                throw new SubscribtionException(Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_error'));
            }
        });
        return $result;
    }

    protected function getCheckedCheckboxesId($post)
    {
        $checked =[];
        $checkboxes = Checkbox::all();
        foreach ($checkboxes as $checkbox) {
            isset($post[$checkbox->slug][1])? array_push($checked, $checkbox->id):'';
        }
        return $checked;
    }

    protected function createSubscriberWithCheckboxes($email, $checked)
    {
        $this->subscriber = new Subscriber();
        $this->subscriber->email = $email;
        $this->subscriber->confirmed = false;
        $this->subscriber->token = hash('sha1', mt_rand(1, 100000) . $this->subscriber->email);
        $this->subscriber->save();
        $this->subscriber->checkboxes()->sync($checked);
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
