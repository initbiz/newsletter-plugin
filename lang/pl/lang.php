<?php

return [
    'plugin' => [
        'name' => 'Newsletter',
        'description' => 'Plugin do zarządzania newsletterem.',
        'author' => 'InIT.biz Ltd.'
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
        'managementpageLabel' => 'Strona zarządzania newsletterem',
        'managementpageDesc' => 'Strona na której został osadzony komponent zarządzający newsletterem',
        'requiredcheckbox' => 'Tekst, który pojawi się przy wymaganym checkboksie',
        'optionalcheckbox' => 'Tekst, który pojawi się przy opcjonalnym checkboksie',
    ],
    'new' => [
        'messages' => 'Nowa wiadomość',
    ],
    'messages' => [
        'title' => 'Tytuł wiadomości',
        'content' => 'Treść wiadomości',
        'slug' => 'Slug',
        'send' => 'Wyślij wiadomość do subskrybentów',
    ],
    'columns' => [
        'newButton' => 'Dodaj wiadomość',
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
        'description' => 'Kod, który otrzyma subskrybent do uwierzytelniania'
    ],
    'email' => [
        'title' => 'Email subskrybenta',
        'description' => 'Email subskrybenta'
    ],
    'confirmedbox' => [
        'message' => 'Dziękujemy za zapisanie się do newslettera'
    ],
];
