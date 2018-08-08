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
        'checkboxes' => 'Checkboxy',
    ],
    'title' => [
        'newsletter' => 'Newsletter',
        'messages' => 'Wiadomości',
        'subscribers' => 'Subskrybenci',
        'checkboxes' => 'Checkboxy',
    ],
    'subscribers' => [
        'import_subscribers' => 'Import subskrybentów',
        'export_subscribers' => 'Eksport subskrybentów',
        'email' => 'E-mail',
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
        'checkbox' => 'Nowy checkbox',
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
        'email_template' => 'Wybierz szablon wiadomości e-mail'
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
        'slug' => 'Slug',
        'email' => 'E-mail',
        'token' => 'Token',
        'confirmed' => 'Potwierdzony',
    ],
    'userColumns' => [
        'email' => 'E-mail',
        'agreement' => 'Zgoda',
        'joined' => 'Dołączył',
        'confirmed' => 'Potwierdzony',
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
        'placeholder_email' => 'E-mail',
        'label_email' => 'E-mail',
        'sign_up_thanks' => 'Dziękujemy za zapisanie się do newslettera!',
        'sign_up_error' => 'Błąd. Coś poszło nie tak.',
    ],
    'manage' => [
        'config_heading' => 'Zmień ustawienia newslettera',
        'sign_out_button_text' => 'Wypisz się z naszego newslettera',
        'update_button_text' => 'Aktualizuj',
    ],
    'ajaxFormResponse' => [
        'sign_up_success' => 'Dziękujemy za zapisanie się do naszego newslettera! Proszę potwierdź email',
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
];
