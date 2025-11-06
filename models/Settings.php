<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Models;

use Cms;
use Model;
use Cms\Classes\Page;
use Initbiz\Newsletter\Classes\Helpers;

class Settings extends Model
{

    use \October\Rain\Database\Traits\Validation;

    public $implement = [
        'System.Behaviors.SettingsModel',
    ];

    public $settingsCode = 'initbiz_newsletter_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public $rules = [
        'recaptcha_score' => 'required|numeric|min:0|max:1',
    ];

    public function initSettingsData()
    {
        $this->send_activation_email = 1;
        $this->enable_mailerlite_integration = false;
        $this->mailerlite_api_key = env('MAILERLITE_API_KEY');
        $this->subscription_manage_page = $this->getSubscriptionManagePage();
        $this->subscription_manage_token_param = $this->getSubscriptionManageTokenParam();
    }

    public function getSubscriptionManagePageOptions(): array
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getNewsletterManagementUrl(string $token): string
    {
        $params = [
            $this->subscription_manage_token_param => $token
        ];

        $subscriptionPage = $this->subscription_manage_page ?? '';
        if (empty($subscriptionPage)) {
            return url('/');
        }

        return Cms::pageUrl($subscriptionPage, $params);
    }

    public static function recaptchaEnable()
    {
        $recaptchaStatus = (int) Settings::get('recaptcha_status');
        $siteKey = Settings::get('recaptcha_site_key');
        $secretKey = Settings::get('recaptcha_secret_key');

        return ($recaptchaStatus === 1 &&
            !empty($siteKey) &&
            !empty($secretKey));
    }

    protected function getSubscriptionManagePage(): ?string
    {
        $page = Helpers::getPageWithComponent('newsletterConfirm');
        if ($page instanceof Page) {
            return $page->getBaseFileName();
        }

        return null;
    }

    protected function getSubscriptionManageTokenParam(): ?string
    {
        $page = Helpers::getPageWithComponent('newsletterConfirm');
        if ($page instanceof Page) {
            $properties = Helpers::getComponentPropertiesFromPage($page, 'newsletterConfirm');
            $tokenVariable = preg_replace('/[^a-zA-Z]|\s/', "", $properties['token']);
            return $tokenVariable;
        }

        return null;
    }
}
