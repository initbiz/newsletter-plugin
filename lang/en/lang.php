<?php

return [
    'plugin' => [
        'name' => 'Newsletter',
        'description' => 'Plugin for newsletter.',
        'author' => 'InIT.biz Ltd.'
    ],
    'mailTemplates' => [
        'message' => 'Message that is being sent to subscribers',
        'confirmation' => 'Mail with confirmation message'
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
        'managementpage_label' => 'Webpage for newsletter managing',
        'managementpage_desc' => 'Webpage where newsletter managing component is located',
        'required_checkbox' => 'Text that will be displayed next to required checkbox',
        'optional_checkbox' => 'Text that will be displayed next to optional checkbox',

    ],
    'new' => [
        'messages' => 'New message',
    ],
    'messages' => [
        'title' => 'Message title',
        'content' => 'Message content',
        'slug' => 'Slug',
        'sent' => 'Message was sent',
        'send' => 'Send message to subscribers',
        'send_to_all' => 'Send message to all subscribers',
        'send_to_agreed' => 'Send message only to those who agreed with second, optional checkbox'
    ],
    'subscribers' => [
        'export_subscribers' => 'Export Subscribers',
        'email' => 'E-mail',
    ],
    'columns' => [
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
    'form' => [
        'button_text' => 'Sign up',
        'sign_up_thanks' => 'Thank you for subscribing our newsletter',
        'sign_up_error' => 'Oops, something went wrong.',
    ],
    'manage' => [
        'button_text' => 'Sign out',
        'unsubscribe_success' => 'Successfully deleted.',
        'unsubscribe_failed' => 'Error. Something went wrong.',
        'thank_you_message' => 'Thank you for signing up to our newsletter!',
        'wrong_path' => 'Wrong path.',
        'update_failed' => 'Error. Something went wrong.',
        'update_success' => 'Successfully deleted.',
    ],
    'ajaxFormResponse' => [
        'sign_up_success' => 'Thank you for signing up to our newsletter!',
        'sign_up_error' => 'Error. Something went wrong.',
        'email_validation_failed' => 'E-mail must be valid',
        'email_cannot_be_empty' => 'E-mail address field cannot be empty',
        'subscriber_save_success' => 'Subscriber successfully saved',
        'subscriber_save_failed' => 'Saving subscriber failed',
    ],
];
