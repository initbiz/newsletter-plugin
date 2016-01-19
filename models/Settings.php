<?php namespace Initbiz\Newsletter\Models;

use Model;
use Cms\Classes\Page as Page;
class Settings extends Model{

    public $implement = [
        'System.Behaviors.SettingsModel'
    ];

    public $settingsCode = 'initbiz_newsletter_settings';

    public $settingsFields = 'fields.yaml';


    public function getManagementPageOptions() {

        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
} 