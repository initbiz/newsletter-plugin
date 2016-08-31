<?php

return [
    'plugin' => [
        'name' => 'Newsletter',
        'description' => 'Plugin do zarządzania newsletterem.',
        'author' => 'InIT.biz Ltd.'
    ],
    'mailTemplates' => [
        'message' => 'Wiadomość, która jest wysyłana do subskrybentów',
        'confirmation' => 'Wiadomość potwierdzająca zapisanie do newslettera'
    ],
    'menu' => [
        'newsletter' => 'Newsletter',
        'messages' => 'Wiadomości',
        'subscribers' => 'Subskrybenci',
    ],
    'title' => [
        'newsletter' => 'Newsletter',
        'messages' => '',
        'subscribers' => 'Subskrybenci',
    ],
    'permission' => [
        'messages' => 'Zarządzanie wiadomościami',
        'subscribers' => 'Zarządzanie subskrybentami',
    ],
    'settings' => [
        'label' => 'Newsletter',
        'description' => 'Ustawienia newslettera',
        'managementpage_label' => 'Strona zarządzania newsletterem',
        'managementpage_desc' => 'Strona na której został osadzony komponent zarządzający newsletterem',
        'required_checkbox' => 'Tekst, który pojawi się przy wymaganym checkboksie',
        'optional_checkbox' => 'Tekst, który pojawi się przy opcjonalnym checkboksie',
    ],
    'new' => [
        'messages' => 'Nowa wiadomość',
    ],
    'messages' => [
        'title' => 'Tytuł wiadomości',
        'content' => 'Treść wiadomości',
        'slug' => 'Slug',
        'send' => 'Wyślij wiadomość do subskrybentów',
        'send_to_all' => 'Wyślij wiadomość do wszystkich subskrybentów',
        'send_to_agreed' => 'Wyślij wiadomość tylko to osób, które zgodziły się z treścią opcjonalną'
    ],
    'columns' => [
        'title' => 'Tytuł',
        'slug' => 'Slug',
        'sent' => 'Wysłano',
        'created' => 'Data utworzenia',
        'updated' => 'Ostatnia aktualizacja',
    ],
    'userColumns' => [
        'email' => 'E-mail',
        'agreement' => 'Zgoda',
        'joined' => 'Dołączył',
    ],
    'flash' => [
        'delete' => 'Czy jesteś pewny że chcesz usunąć zaznaczone elementy?',
        'deleted' => 'Usunięto wybrane elementy',
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
        'sign_up_thanks' => 'Dziękujemy za zapisanie się do newslettera!',
        'sign_up_error' => 'Błąd. Coś poszło nie tak.',
    ],
    'manage' => [
        'button_text' => 'Wypisz się',
        'unsubscribe_success' => 'Pomyślnie usunięto.',
        'unsubscribe_failed' => 'Błąd. Coś poszło nie tak.',
        'thank_you_message' => 'Dziękujemy za zapisanie się do newslettera',
        'wrong_path' => 'Niepoprawna ścieżka',
    ],
    'ajaxFormResponse' => [
        'email_validation_failed' => 'Adres e-mail musi być poprawny i nie może być pusty',
        'subscriber_save_success' => 'Pomyślnie zapisano subskrybenta',
        'subscriber_save_failed' => 'Zapisywanie subskrybenta się nie powiodło',
        'email_cannot_be_empty' => 'E-mail nie może być pusty',
        'subscriber_save_success' => 'Pomyślnie zapisano subskrybenta',
        'subscriber_save_failed' => 'Zapisywanie subskrybenta się nie powiodło',
    ],
];
