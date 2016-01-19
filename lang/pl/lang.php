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
        'newsletter' => 'Zarządzanie newsletterem',
        'messages' => 'Zarządzanie wiadomościami',
        'subscribers' => 'Zarządzanie subskrybentów',
    ],
    'settings' => [
        'label' => 'Newsletter',
        'description' => 'Ustawienia newslettera',
        'managementpageLabel' => 'Strona zarządzania newsletterem',
        'managementpageDesc' => 'Strona na której został osadzony komponent zarządzający newsletterem',
    ],
    'new' => [
        'messages' => 'Nowa wiadomość',
        'subscribers' => 'Nowy subskrybent',
    ],
    'messages' => [
        'title' => 'Tytuł wiadomości',
        'content' => 'Treść wiadomości',
        'slug' => 'Slug',
        'send' => 'Wyślij wiadomość do subskrybentów',
        'submit' => 'Zapisz się!',
    ],
    'columns' => [
        'newButton' => 'Dodaj wiadomość',
        'title' => 'Tytuł',
        'slug' => 'Slug',
        'status' => 'Status',
        'created' => 'Data utworzenia',
        'updated' => 'Ostatnia aktualizacja',
    ],
    'userColumns' => [
        'email' => 'E-mail',
        'confirmed' => 'Potwierdzony',
        'agreement' => 'Zgoda',
        'joined' => 'Dołączył',
    ],
];
