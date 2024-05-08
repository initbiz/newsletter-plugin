<?php

namespace Initbiz\Newsletter\Models;

use Model;

class Settings extends Model
{
    public $implement = [
        'System.Behaviors.SettingsModel',
    ];

    public $settingsCode = 'initbiz_newsletter_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public function initSettingsData()
    {
        $this->enable_mailerlite_integration = false;
        $this->mailerlite_api_key = env('MAILERLITE_API_KEY');
    }
}
