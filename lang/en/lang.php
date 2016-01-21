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
        'requiredcheckbox' => 'Text that will be displayed next to required checkbox',
        'optionalcheckbox' => 'Text that will be displayed next to optional checkbox',
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
        'sent' => 'Sent',
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
	'deleted' => 'Succesfully deleted selected items',
    ],
    'token' => [
        'title' => 'Subscriber unique code',
        'description' => 'Code that subscriber will get and can use to authorize'
    ],
    'email' => [
        'title' => 'Subscribers e-mail address',
        'description' => 'Subscribers e-mail address'
    ],
    'confirmedbox' => [
        'message' => 'Thank you for signing up to our newsletter'
    ],
];
