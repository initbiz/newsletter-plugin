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
        Db::transaction(function () use (&$result) {
            $data = post();

            $rules = [
                'email'    => 'required|email|between:6,255|unique:initbiz_newsletter_subscribers'
            ];

            $validation = Validator::make($data, $rules);

            if ($validation->fails()) {
                throw new ValidationException($validation);
            }

            // check if all required checkboxes are checked
            $requiredCheckboxes = Checkbox::required()->get();
            $checkedCheckboxes = $this->getCheckedCheckboxesId($data);

            //If currently checked checkboxes does not contain any of required checkboxes than throw
            foreach ($requiredCheckboxes as $requiredCheckbox) {
                if (!in_array($requiredCheckbox->id, $checkedCheckboxes)) {
                    throw new ValidationException(['requiredCheckboxes' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.checkbox_validation_failed')]);
                }
            }

            try {
                $this->createSubscriberWithCheckboxes($data['email'], $checkedCheckboxes);
                $this->sendActivationEmail();
                $result = [
                    'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_success')
                ];
            } catch (Exception $e) {
                throw new SubscribtionException(Lang::get('initbiz.newsletter::lang.ajaxFormResponse.sign_up_error'));
            }
        });
        return $result;
    }

    /**
     * Get IDs of checked checkboxes from DB
     * @param  array $data array of sent data
     * @return array       array of checked checkboxes IDs
     */
    protected function getCheckedCheckboxesId($data)
    {
        $checked =[];
        $checkboxes = Checkbox::all();
        foreach ($checkboxes as $checkbox) {
            //If value in data is set than it means the checkbox is checked
            if (isset($data[$checkbox->slug][1])) {
                $checked[] = $checkbox->id;
            }
        }
        return $checked;
    }

    /**
     * Create subscriber with relations to checkboxes
     * @param  string $email   subscriber's email
     * @param  array  $checked array of checked checkboxes by the subscriber
     * @return void
     */
    protected function createSubscriberWithCheckboxes($email, $checked)
    {
        $this->subscriber = new Subscriber();
        $this->subscriber->email = $email;
        $this->subscriber->confirmed = false;
        $this->subscriber->token = hash('sha1', mt_rand(1, 100000) . $this->subscriber->email);
        $this->subscriber->save();
        $this->subscriber->checkboxes()->sync($checked);
    }

    /**
     * Send activation email to $this->subscriber
     * @return void
     */
    protected function sendActivationEmail()
    {
        $options = [
            'recipient_email' => $this->title,
            'subject' => $this->title,
            'template' => 'initbiz.newsletter::mail.subscription',
            'activationLink' => Helpers::getNewsletterManagementUrl($this->subscriber->email, $this->subscriber->token)
        ];
        Helpers::sendMail($options);
    }
}
