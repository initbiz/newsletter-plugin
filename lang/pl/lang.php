<?php

return [
    'plugin' => [
        'name' => 'Newsletter',
        'description' => 'Plugin do zarządzania newsletterem.',
        'author' => 'InIT.biz Ltd.',
    ],

    'mailTemplates' => [
        'message' => 'Wiadomość, która jest wysyłana do subskrybentów',
        'confirmation' => 'Wiadomość potwierdzająca zapisanie do newslettera',
    ],

    'settings' => [
        'label' => 'Ustawienia newslettera',
        'description' => 'Zarządzaj pluginem newsletter',
        'general_tab' => 'Ogólne',
        'additional_fields_tab' => 'Dodatkowe pola',
        'integrations_tab' => 'Integracje',
        'send_activation_email' => 'Wyślij maila aktywacyjnego',
        'subscription_manage_page' => 'Strona zarządzania subskrypcją',
        'subscription_manage_token_param' => 'Parametr token',
        'additional_fields_attribute' => 'Atrybut',
        'additional_fields_label' => 'Nazwa',
        'additional_fields_type' => 'Typ',
        'additional_fields_type_text' => 'Text',
        'additional_fields_type_email' => 'E-mail',
        'additional_fields_type_number' => 'Liczba',
        'additional_fields_input_placeholder' => 'Placeholder',
        'additional_fields_rules' => 'Zasady walidacji',
        'additional_fields_rules_comment' => 'Zobacz <a href="https://docs.octobercms.com/3.x/extend/services/validation.html" target="_blank">walidację w OctoberCMS</a>',
        'enable_mailerlite_integration' => 'Włącz integrację z MailerLite',
        'mailerlite_api_key' => 'Klucz API MailerLite',
    ],

    'menu' => [
        'newsletter' => 'Newsletter',
        'messages' => 'Wiadomości',
        'subscribers' => 'Subskrybenci',
        'checkboxes' => 'Checkboxy',
        'tags' => 'Tagi',
    ],

    'tag' => [
        'name' => 'Nazwa',
        'slug' => 'Slug',
        'additional_data_tab' => 'Dodatkowe dane',
    ],

    'subscriber' => [
        'confirmed' => 'Potwierdzony',
        'settings_tab' => 'Ustawienia',
        'details_tab' => 'Szczegóły',
        'additional_fields_tab' => 'Dodatkowe pola',
        'additional_data_tab' => 'Dodatkowe dane',
        'additional_fields_comment' => 'Te pola zostaną wysłane do integracji',
        'email' => 'E-mail',
        'first_name' => 'Imię',
        'last_name' => 'Nazwisko',
        'checkboxes' => 'Checkboxy',
        'tags' => 'Tagi',
        'token' => 'Token',
        'address_line1' => 'Linia adresu 1',
        'address_line2' => 'Linia adresu 2',
        'company' => 'Firma',
        'sex' => 'Płeć',
        'sex_male' => 'Mężczyzna',
        'sex_female' => 'Kobieta',
        'sex_other' => 'inna',
        'age' => 'Wiek',
        'phone' => 'Numer telefonu',
        'city' => 'Miejscowość',
        'zip' => 'Kod pocztowy',
        'date_of_birth' => 'Data urodzenia',
    ],

    'title' => [
        'newsletter' => 'Newsletter',
        'messages' => 'Wiadomości',
        'subscribers' => 'Subskrybenci',
        'checkboxes' => 'Checkboxy',
    ],

    'form_component' => [
        'tags' => 'Tagi dodane subskrybentom',
        'confirm_automatically' => 'Automatycznie potwierdź',
        'inputs' => 'Pola',
        'button_text' => 'Napis na przycisku',
    ],

    'subscribers' => [
        'import_subscribers' => 'Import subskrybentów',
        'export_subscribers' => 'Eksport subskrybentów',
        'email' => 'E-mail',
    ],

    'permission' => [
        'messages' => 'Zarządzanie wiadomościami',
        'subscribers' => 'Zarządzanie subskrybentami',
        'tags' => 'Zarządzanie tagami newslettera',
        'settings' => 'Dostęp do ustawień pluginu Newsletter',
    ],

    'new' => [
        'messages' => 'Nowa wiadomość',
        'checkbox' => 'Nowy checkbox',
        'subscriber' => 'Nowy subskrybent',
    ],

    'checkboxes' => [
        'export' => 'Wyeksportuj Checkboxy',
        'import' => 'Zaimportuj Checkboxy',
        'name' => 'Nazwa',
        'text' => 'Tekst pojawiający sie przy checkboxie',
        'required' => 'Wymagany',
    ],

    'messages' => [
        'title' => 'Tytuł wiadomości',
        'content' => 'Treść wiadomości',
        'slug' => 'Slug',
        'sent' => 'Wiadomość została wysłana',
        'send' => 'Wyślij wiadomość do subskrybentów',
        'send_to_all' => 'Wyślij wiadomość do wszystkich subskrybentów',
        'send_to_agreed' => 'Wyślij wiadomość tylko to osób, które zgodziły się z treścią opcjonalną',
        'email_template' => 'Wybierz szablon wiadomości e-mail',
    ],

    'columns' => [
        'title' => 'Tytuł',
        'slug' => 'Slug',
        'sent' => 'Wysłano',
        'created' => 'Data utworzenia',
        'updated' => 'Ostatnia aktualizacja',
        'name' => 'Nazwa',
        'text' => 'Tekst',
        'required' => 'Wymagany',
        'email' => 'E-mail',
        'token' => 'Token',
        'confirmed' => 'Potwierdzony',
        'checkboxes' => 'Checkboxy',
        'tags' => 'Tagi',
    ],

    'userColumns' => [
        'email' => 'E-mail',
        'agreement' => 'Zgoda',
        'joined' => 'Dołączył',
        'confirmed' => 'Potwierdzony',
        'tags' => 'Tagi',
    ],

    'flash' => [
        'delete' => 'Czy jesteś pewny że chcesz usunąć zaznaczone elementy?',
        'deleted' => 'Usunięto wybrane elementy',
    ],

    'flash_checkboxes' => [
        'deleted' => 'Checkbox pomyślnie usunięty',
        'saved' => 'Checkbox pomyślnie zapisany',
        'updated' => 'Checkbox pomyślnie zaktualizowany',
    ],

    'flash_checkboxes' => [
        'deleted' => 'Subskrybent pomyślnie usunięty',
        'saved' => 'Subskrybent pomyślnie zapisany',
        'updated' => 'Subskrybent pomyślnie zaktualizowany ',
    ],

    'token' => [
        'title' => 'Kod subskrybenta',
        'description' => 'Kod, który otrzyma subskrybent do uwierzytelniania',
    ],

    'email' => [
        'title' => 'Email subskrybenta',
        'description' => 'Email subskrybenta',
    ],

    'confirmedbox' => [
        'message' => 'Dziękujemy za zapisanie się do newslettera',
    ],

    'form' => [
        'button_text' => 'Zapisz się',
        'placeholder_email' => 'E-mail',
        'label_email' => 'E-mail',
        'placeholder_first_name' => 'Imię',
        'placeholder_last_name' => 'Nazwisko',
        'placeholder_address_line1' => 'Adres',
        'placeholder_address_line2' => 'Adres 2',
        'placeholder_company' => 'Firma',
        'placeholder_sex' => 'Płeć',
        'placeholder_age' => 'Wiek',
        'placeholder_phone' => 'Nr telefonu',
        'placeholder_city' => 'Miasto',
        'placeholder_zip' => 'Kod pocztowy',
        'placeholder_date_of_birth' => 'Data urodzenia',
        'sign_up_thanks' => 'Dziękujemy za zapisanie się do newslettera!',
        'sign_up_error' => 'Błąd. Coś poszło nie tak.',
    ],

    'manage' => [
        'thank_you_message' => 'Dziękujemy za zapisanie się do naszego newslettera',
        'config_heading' => 'Zmień ustawienia newslettera',
        'sign_out_button_text' => 'Wypisz się z naszego newslettera',
        'update_button_text' => 'Aktualizuj',
    ],

    'ajaxFormResponse' => [
        'sign_up_success' => 'Dziękujemy za zapisanie się!',
        'sign_up_error' => 'Coś poszło nie tak',
        'email_validation_failed' => 'E-mail musi być poprawny',
        'email_cannot_be_empty' => 'Pole E-mail nie może być puste',
        'subscriber_save_success' => 'Subskrybent zapisany poprawnie',
        'subscriber_save_failed' => 'Zapisanie subskrybentan nie powiodło się',
        'checkbox_validation_failed' => 'Musisz zaznaczyć wszystkie wymagane zgody',
        'unsubscribe_success' => 'Wypisałeś się z newslettera pomyślnie',
        'unsubscribe_failed' => 'Coś poszło nie tak',
        'wrong_path' => 'Zła ścieżka',
        'update_failed' => 'Coś poszło nie tak',
        'update_success' => 'Zaktualizowano pomyślnie',
        'unsubscribe' => 'Wypisz',
        'error' => 'Coś poszło nie tak',
    ],

    'mail' => [
        'activation_subject' => 'Potwierdź swój adres e-mail',
    ],
];
