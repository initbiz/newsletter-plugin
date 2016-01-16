<?php namespace Initbiz\Newsletter;

use System\Classes\PluginBase;
use Backend;

/**
 * newsletter Plugin Information File by InIT.biz Ltd. http://init.biz
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

    public function registerNavigation()
    {
        return [
            'newsletter' => [
                    'label'         => 'initbiz.newsletter::lang.menu.newsletter',
                    'url'           => Backend::url('initbiz/newsletter/messages'),
                    'icon'          => 'icon-envelope-o',
                    'permissions'   => ['initbiz.newsletter.*'],
                    'order'         => 500,

                    'sideMenu'  => [
                        'messages'  => [
                            'label'         => 'initbiz.newsletter::lang.menu.messages',
                            'url'           =>  Backend::url('initbiz/newsletter/messages'),
                            'icon'          =>  'icon-envelope',
                            'permissions'   => ['initbiz.newsletter.messages']
                        ],
                        'subscribers' => [
                            'label'         => 'initbiz.newsletter::lang.menu.subscribers',
                            'url'           =>  Backend::url('initbiz/newsletter/subscribers'),
                            'icon'          =>  'icon-male',
                            'permissions'   => ['initbiz.newsletter.subscribers']
                        ]
                    ]
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            'Initbiz\Newsletter\Components\NewsletterConfirm' => 'newsletterConfirm',
            'Initbiz\Newsletter\Components\Form' => 'newsletterForm'
        ];
    }

    public function registerPermissions()
    {
        return [
            'initbiz.newsletter.messages'   =>  [
                'tab'   =>  'initbiz.newsletter::lang.menu.newsletter',
                'label' =>  'initbiz.newsletter::lang.permission.messages'
            ],
            'initbiz.newsletter.subscribers'   =>  [
                'tab'   =>  'initbiz.newsletter::lang.menu.newsletter',
                'label' =>  'initbiz.newsletter::lang.permission.subscribers'
            ]
        ];
    }

}
