<?php

declare(strict_types=1);

return [
    'plugin' => [
        'name' => 'Newsletter',
        'description' => 'Plugin for newsletter.',
        'author' => 'InIT.biz Ltd.'
    ],

    'settings' => [
        'label' => 'Newsletter settings',
        'description' => 'Manage newsletter plugin',
        'general_tab' => 'General',
        'additional_fields_tab' => 'Additional fields',
        'integrations_tab' => 'Integrations',
        'send_activation_email' => 'Send activation email on sign up',
        'subscription_manage_page' => 'Subscription manage page',
        'subscription_manage_token_param' => 'Token parameter',
        'additional_fields_attribute' => 'Attribute',
        'additional_fields_label' => 'Label',
        'additional_fields_type' => 'Type',
        'additional_fields_type_text' => 'Text',
        'additional_fields_type_email' => 'E-mail',
        'additional_fields_type_number' => 'Number',
        'additional_fields_input_placeholder' => 'Placeholder',
        'additional_fields_rules' => 'Validation rules',
        'additional_fields_rules_comment' => 'See <a href="https://docs.octobercms.com/3.x/extend/services/validation.html" target="_blank">OctoberCMS validation rules</a>',
        'enable_mailerlite_integration' => 'Enable MailerLite integration',
        'mailerlite_api_key' => 'MailerLite API key',
        'mailerlite_webhook_secret' => 'MailerLite webhook signing secret',
        'mailerlite_webhook_secret_comment' => 'Set [your-domain]/api/initbiz/newsletter/mailerlite <a target="_blank" href="https://dashboard.mailerlite.com/integrations/webhooks">here</a>',
    ],

    'menu' => [
        'newsletter' => 'Newsletter',
        'messages' => 'Messages',
        'subscribers' => 'Subscribers',
        'checkboxes' => 'Checkboxes',
        'tags' => 'Tags',
    ],

    'tag' => [
        'name' => 'Name',
        'slug' => 'Slug',
        'additional_data_tab' => 'Additional data',
    ],

    'subscriber' => [
        'confirmed' => 'Confirmed',
        'settings_tab' => 'Settings',
        'details_tab' => 'Details',
        'additional_fields_tab' => 'Additional fields',
        'additional_data_tab' => 'Additional data',
        'additional_fields_comment' => 'These fields will be sent to integrations',
        'email' => 'E-mail',
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'checkboxes' => 'Checkboxes',
        'tags' => 'Tags',
        'token' => 'Token',
        'address_line1' => 'Address line 1',
        'address_line2' => 'Address line 2',
        'company' => 'Company',
        'sex' => 'Sex',
        'sex_male' => 'Male',
        'sex_female' => 'Female',
        'sex_other' => 'Other',
        'age' => 'Age',
        'phone' => 'Phone',
        'city' => 'City',
        'zip' => 'ZIP',
        'date_of_birth' => 'Date of birth',
    ],

    'title' => [
        'newsletter' => 'Newsletter',
        'messages' => 'message',
        'subscribers' => 'Subscribers',
        'checkboxes' => 'Checkboxes',
    ],

    'form_component' => [
        'name' => 'Newsletter form',
        'description' => 'Newsletter form component',
        'tags' => 'Tags to add the subscriber',
        'confirm_automatically' => 'Confirm automatically',
        'inputs' => 'Inputs',
        'button_text' => 'Text on button',
        'ref' => 'Ref',
        'ref_description' => 'Reference string to identify this form',
        'custom_view_path' => 'Custom view directory',
        'custom_view_path_description' => 'Set this to the name of directory in your theme/partials to overwrite the view',
    ],

    'subscribers' => [
        'import_subscribers' => 'Import subscribers',
        'export_subscribers' => 'Export subscribers',
        'email' => 'E-mail',
    ],

    'permission' => [
        'messages' => 'Messages managing',
        'subscribers' => 'Subscribers managing',
        'tags' => 'Newsletter tags managing',
        'settings' => 'Access to newsletter settings',
    ],

    'new' => [
        'messages' => 'New message',
        'checkbox' => 'New checkbox',
        'subscriber' => 'New subscriber',
    ],

    'checkboxes' => [
        'export' => 'Export Checkboxes',
        'import' => 'Import Checkboxes',
        'name' => 'Name',
        'text' => 'Text appearing next to the checkbox',
        'required' => 'Required',
    ],

    'messages' => [
        'title' => 'Message title',
        'content' => 'Message content',
        'slug' => 'Slug',
        'sent' => 'Message was sent',
        'send' => 'Send message to subscribers',
        'send_to_all' => 'Send message to all subscribers',
        'send_to_agreed' => 'Send message only to those who agreed optional checkbox',
        'email_template' => 'Select a template of e-mail to use'
    ],

    'columns' => [
        'title' => 'Title',
        'slug' => 'Slug',
        'sent' => 'Sent',
        'created' => 'Created',
        'updated' => 'Updated',
        'name' => 'Name',
        'text' => 'Text',
        'required' => 'Required',
        'email' => 'E-mail',
        'token' => 'Token',
        'confirmed' => 'Confirmed',
        'checkboxes' => 'Checkboxes',
        'tags' => 'Tags',
    ],

    'user_columns' => [
        'email' => 'E-mail',
        'agreement' => 'Agreement',
        'joined' => 'Joined',
        'confirmed' => 'Confirmed',
        'tags' => 'Tags',
        'slug' => 'Slug',
    ],

    'flash' => [
        'delete' => 'Are you sure you want to delete this checkbox?',
        'delete_subscriber' => 'Are you sure you want to delete this subscriber?',
    ],

    'flash_checkboxes' => [
        'deleted' => 'Checkbox successfully deleted',
        'saved' => 'Checkbox successfully saved',
        'updated' => 'Checkbox successfully updated',
    ],

    'flash_subscribers' => [
        'deleted' => 'Subscriber successfully deleted',
        'saved' => 'Subscriber successfully saved',
        'updated' => 'Subscriber successfully updated',
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
        'placeholder_email' => 'E-mail',
        'label_email' => 'E-mail address',
        'placeholder_first_name' => 'Name',
        'placeholder_last_name' => 'Surname',
        'placeholder_address_line1' => 'Address',
        'placeholder_address_line2' => 'Address 2',
        'placeholder_company' => 'Company',
        'placeholder_sex' => 'Sex',
        'placeholder_age' => 'Age',
        'placeholder_phone' => 'Phone',
        'placeholder_city' => 'City',
        'placeholder_zip' => 'Zip code',
        'placeholder_date_of_birth' => 'Date of birth',
        'sign_up_thanks' => 'Thank you for subscribing our newsletter',
        'sign_up_error' => 'Oops, something went wrong.',
    ],

    'manage' => [
        'thank_you_message' => 'Thank you for subscribing our newsletter',
        'config_heading' => 'Customize your newsletter configuration',
        'sign_out_button_text' => 'Sign out from our newsletter',
        'update_button_text' => 'Update'
    ],

    'ajaxFormResponse' => [
        'sign_up_success' => 'Thank you for signing up!',
        'sign_up_error' => 'Error. Something went wrong.',
        'email_validation_failed' => 'E-mail must be valid',
        'email_cannot_be_empty' => 'E-mail address field cannot be empty',
        'subscriber_save_success' => 'Subscriber successfully saved',
        'subscriber_save_failed' => 'Saving subscriber failed',
        'checkbox_validation_failed' => 'You must accept all required checkboxes',
        'unsubscribe_success' => 'Successfully unsubscribe.',
        'unsubscribe_failed' => 'Error. Something went wrong.',
        'wrong_path' => 'Wrong path.',
        'update_failed' => 'Error. Something went wrong.',
        'update_success' => 'Successfully updated.',
        'unsubscribe' => 'Unsubscribe',
        'error' => 'Error. Something went wrong.',
    ],

    'mail' => [
        'activation_subject' => 'Confirm your e-mail address'
    ],

    // Notify

    'events_group' => [
        'name' => 'Newsletter',
    ],

    'form_submitted_event' => [
        'name' => 'Newsletter form submitted',
        'description' => 'Triggers when somebody submits the newsletter form',
        'ref' => 'Reference of the form that was submitted',
        'post_data' => 'Data sent with the form',
        'url' => 'URL that the form was submitted from',
        'subscriber' => 'Subscriber instance',
        'checked_checkboxes' => 'List of checked checkboxes',
        'tags' => 'List of tags to attach using the form',
    ],

    'particular_ref_condition' => [
        'name' => 'Trigger the event only when ref matches',
        'text' => 'Ref is :ref',
        'ref' => 'Reference of the form to compare',
        'ref_comment' => 'You can type many refs comma separated',
    ],

    'particular_tag_condition' => [
        'name' => 'Trigger the event only when tag matches',
        'text' => 'Tag is one of: :tags',
        'tags' => 'Tags',
    ],
];
