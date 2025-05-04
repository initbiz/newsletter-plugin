<?php

namespace Initbiz\Newsletter;

use Event;
use Backend;
use System\Classes\PluginBase;
use Initbiz\Newsletter\Models\Settings;
use Cms\Classes\Snippet;

/**
 * Newsletter plugin
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'initbiz.newsletter::lang.plugin.name',
            'description' => 'initbiz.newsletter::lang.plugin.description',
            'author'      => 'initbiz.newsletter::lang.plugin.author',
            'icon'        => 'icon-envelope-o'
        ];
    }

    public function boot()
    {
        Event::subscribe(\Initbiz\Newsletter\EventHandlers\MailerLiteHandler::class);

        /**
         * To be removed if https://github.com/octobercms/october-private/issues/697
         * is fixed
         */
        // Event::listen('pages.snippets.listSnippets', function($manager) {
        //     $snippet = new Snippet();
        //     $snippet->initFromComponentInfo(\Initbiz\Newsletter\Components\Form::class, 'newsletterForm');
        //     $manager->addSnippet($snippet);
        // });
    }

    public function registerNavigation()
    {
        return [
            'newsletter' => [
                'label'         => 'initbiz.newsletter::lang.menu.newsletter',
                'url'           => Backend::url('initbiz/newsletter/subscribers'),
                'icon'          => 'icon-envelope-o',
                'permissions'   => ['initbiz.newsletter.*'],
                'order'         => 500,

                'sideMenu'  => [
                    'subscribers' => [
                        'label'         => 'initbiz.newsletter::lang.menu.subscribers',
                        'url'           =>  Backend::url('initbiz/newsletter/subscribers'),
                        'icon'          =>  'icon-male',
                        'permissions'   => ['initbiz.newsletter.subscribers']
                    ],
                    'tags' => [
                        'label'         => 'initbiz.newsletter::lang.menu.tags',
                        'url'           =>  Backend::url('initbiz/newsletter/tags'),
                        'icon'          =>  'icon-tag',
                        'permissions'   => ['initbiz.newsletter.tags']
                    ],
                    'checkboxes' => [
                        'label'         => 'initbiz.newsletter::lang.menu.checkboxes',
                        'url'           =>  Backend::url('initbiz/newsletter/checkboxes'),
                        'icon'          =>  'oc-icon-cog',
                        'permissions'   => ['initbiz.newsletter.checkboxes']
                    ],
                    'messages'  => [
                        'label'         => 'initbiz.newsletter::lang.menu.messages',
                        'url'           =>  Backend::url('initbiz/newsletter/messages'),
                        'icon'          =>  'icon-envelope',
                        'permissions'   => ['initbiz.newsletter.messages']
                    ],
                ]
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            \Initbiz\Newsletter\Components\NewsletterConfirm::class => 'newsletterConfirm',
            \Initbiz\Newsletter\Components\Form::class => 'newsletterForm'
        ];
    }

    public function registerPageSnippets()
    {
        return [
            \Initbiz\Newsletter\Components\Form::class => 'newsletterForm'
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'initbiz.newsletter::mail.message' => 'initbiz.newsletter::lang.mailTemplates.message',
            'initbiz.newsletter::mail.subscription' => 'initbiz.newsletter::lang.mailTemplates.confirmation',
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'initbiz.newsletter::lang.settings.label',
                'description' => 'initbiz.newsletter::lang.settings.description',
                'icon'        => 'icon-envelope',
                'class'       => Settings::class,
                'order'       => 100,
                'permissions' => ['initbiz.newsletter.settings'],
            ],
        ];
    }


    public function registerPermissions()
    {
        return [
            'initbiz.newsletter.settings'   =>  [
                'tab'   =>  'initbiz.newsletter::lang.menu.newsletter',
                'label' =>  'initbiz.newsletter::lang.permission.settings'
            ],
            'initbiz.newsletter.messages'   =>  [
                'tab'   =>  'initbiz.newsletter::lang.menu.newsletter',
                'label' =>  'initbiz.newsletter::lang.permission.messages'
            ],
            'initbiz.newsletter.tags'   =>  [
                'tab'   =>  'initbiz.newsletter::lang.menu.newsletter',
                'label' =>  'initbiz.newsletter::lang.permission.tags'
            ],
            'initbiz.newsletter.subscribers'   =>  [
                'tab'   =>  'initbiz.newsletter::lang.menu.newsletter',
                'label' =>  'initbiz.newsletter::lang.permission.subscribers'
            ],
            'initbiz.newsletter.checkboxes'   =>  [
                'tab'   =>  'initbiz.newsletter::lang.menu.checkboxes',
                'label' =>  'initbiz.newsletter::lang.permission.checkboxes'
            ]
        ];
    }
}
