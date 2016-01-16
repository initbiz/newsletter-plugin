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
            'code' => [
                'title'       => 'Kod subskrybenta',
                'description' => 'Kod subskrybenta',
                'default'     => '{{ :code }}',
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

    public function onRun()
    {
        $this->code = $this->page['code'] = $this->property('code');
        $this->mail = $this->page['mail'] = $this->property('mail');
    }

    protected function loadPost()
    {
        $slug = $this->property('slug');
        $post = NewsPost::isPublished()->where('slug', $slug)->first();

        return $post;
    }
}
