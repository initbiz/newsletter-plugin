<?php

return [
    'plugin' => [
        'name' => 'Newsletter',
        'description' => 'Plugin for newsletter.',
        'author' => 'InIT.biz Ltd.'
    ],
    'menu' => [
        'newsletter' => 'Newsletter',
        'messages' => 'Messages',
        'subscribers' => 'Subscribers',
    ],
    'title' => [
        'newsletter' => 'Newsletter',
        'messages' => 'message',
        'subscribers' => 'Subscribers',
    ],
    'permission' => [
        'messages' => 'Messages managing',
        'subscribers' => 'Subscribers managing',
    ],
    'settings' => [
        'label' => 'Newsletter',
        'description' => 'Newsletter settings',
        'managementpageLabel' => 'Webpage for newsletter managing',
        'managementpageDesc' => 'Webpage where newsletter managing component is located',
    ],
    'new' => [
        'messages' => 'New message',
    ],
    'messages' => [
        'title' => 'Message title',
        'content' => 'Message content',
        'slug' => 'Slug',
        'send' => 'Send message to subscribers',
    ],
    'columns' => [
        'newButton' => 'Add message',
        'title' => 'Title',
        'slug' => 'Slug',
        'status' => 'Status',
        'created' => 'Created',
        'updated' => 'Updated',
    ],
    'userColumns' => [
        'email' => 'E-mail',
        'agreement' => 'Agreement',
        'joined' => 'Joined',
    ],
    'flash' => [
        'delete' => 'Are you sure you want to delete selected items?',
    ],
];
