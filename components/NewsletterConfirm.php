<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Components;

use Db;
use Lang;
use Cms\Classes\ComponentBase;
use Initbiz\Newsletter\Models\Checkbox;
use Initbiz\Newsletter\Models\Subscriber as Subscriber;
use Initbiz\Newsletter\Classes\UpdateSubscriberException;

class NewsletterConfirm extends ComponentBase
{
    public $subscriber;
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

    public function onRun()
    {
        $this->token = $this->page['token'] = $this->property('token');

        $subscriber = Subscriber::where('token', $this->token)->first();
        if (!$subscriber) {
            $this->setStatusCode(404);
            return $this->controller->run('404');
        }

        $this->page['confirmed'] = true;
        $this->subscriber = $subscriber;

        $this->email = $this->page['email'] = $subscriber->email;

        $userCheckboxes = $this->getSubscriberCheckboxesSlugs($subscriber);
        $notRequiredCheckboxes = $this->getAllNotRequiredCheckboxes()->toArray();
        $checkedNotRequiredCheckboxes = $this->addToCheckboxesIfChecked($notRequiredCheckboxes, $userCheckboxes);
        $this->page['checkboxes'] = $checkedNotRequiredCheckboxes;

        if (!$subscriber->confirmed) {
            $subscriber->activate();
        }
    }

    public function onUnsubscribe(array $data = [])
    {
        if (empty($data)) {
            $data = post();
        }

        $subscriber = Subscriber::where('token', $data['token'])->first();
        if (!$subscriber) {
            return;
        }

        $subscriber->delete();

        return [
            'content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.unsubscribe_success'),
            'redirectUrl' => url('/')
        ];
    }

    protected function getSubscriberCheckboxesSlugs($subscriber)
    {
        return Checkbox::whereHas('subscribers', function ($q) use ($subscriber) {
            return $q->where('id', $subscriber->id);
        })->notRequired()
            ->get()
            ->pluck('slug')
            ->toArray();
    }

    protected function addToCheckboxesIfChecked($notRequiredCheckboxes, $userCheckboxes)
    {
        foreach ($notRequiredCheckboxes as &$checkbox) {
            if (in_array($checkbox['slug'], $userCheckboxes, true)) {
                $checkedArray = ['checked' => true];
                $checkbox += $checkedArray;
            }
        }
        return $notRequiredCheckboxes;
    }

    protected function getAllNotRequiredCheckboxes()
    {
        return Checkbox::notRequired()->get();
    }

    public function onUpdate()
    {
        $result = [];
        Db::transaction(function () use (&$result) {
            try {
                $data = post();
                $this->updateSubscriberCheckboxes($data);
                $result = ['content' => Lang::get('initbiz.newsletter::lang.ajaxFormResponse.update_success')];
            } catch (\Exception $e) {
                throw new UpdateSubscriberException(Lang::get('initbiz.newsletter::lang.ajaxFormResponse.update_failed'));
            }
        });

        return $result;
    }

    protected function updateSubscriberCheckboxes($data)
    {
        $checkboxes = $this->getAllNotRequiredCheckboxes();
        $subscriber = $this->getSubscriber($data['token'], $data['email']);
        $subscriber->checkboxes()->detach();

        foreach ($checkboxes as $checkbox) {
            $checkbox = Checkbox::where('slug', $checkbox->slug)->firstOrFail();
            if (post($checkbox->slug) !== null) {
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
