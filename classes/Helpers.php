<?php namespace Initbiz\Newsletter\Classes;

use Mail;
use Cache;
use Cms\Classes\Theme;
use Cms\Classes\Page as CmsPage;

class Helpers
{
    /**
     * Send email using one method globally
     * @param  array $options options to send email
     * @return void
     */
    public static function sendMail($options)
    {
        //TODO: Validation?
        //$options needs to have: recipient_name (optional), recipient_email, subject, template + other variables
        $recipient_name = (isset($options['recipient_name'])) ? $options['recipient_name']: $options['recipient_email'];
        $recipient_email = $options['recipient_email'];
        $subject = $options['subject'];

        Mail::send($options['template'], $options, function ($message) use ($recipient_email, $recipient_name, $subject) {
            $message->to($recipient_email, $recipient_name);
            $message->subject($subject);
        });
    }

    /**
     * Get newsletter management page url with injected email and token
     * @param  string $email subscriber's email
     * @param  string $token substriber's token
     * @return string        url of the newsletter management page
     */
    public static function getNewsletterManagementUrl($email, $token)
    {
        //TODO it sucks, need to change normal url parsing and finding page globally

        $pageUrl = Cache::get('newsletterManagementUrl');
        $emailVariable = Cache::get('newsletterManagementEmailVariable');
        $tokenVariable = Cache::get('newsletterManagementTokenVariable');

        if (!$pageUrl || !$emailVariable || !$tokenVariable) {
            $page = self::getPageWithComponent('newsletterConfirm');
            $properties = self::getComponentPropertiesFromPage($page, 'newsletterConfirm');

            $pageUrl = $page->url;
            $emailVariable = preg_replace('/[^a-zA-Z:]|\s/', "", $properties['email']);
            $tokenVariable = preg_replace('/[^a-zA-Z:]|\s/', "", $properties['token']);

            Cache::put('newsletterManagementUrl', $pageUrl, 10);
            Cache::put('newsletterManagementEmailVariable', $emailVariable, 10);
            Cache::put('newsletterManagementTokenVariable', $tokenVariable, 10);
        }

        $managementPageUrl = $pageUrl;
        $managementPageUrl = preg_replace('/'.$emailVariable.'/', $email, $managementPageUrl);
        $managementPageUrl = preg_replace('/'.$tokenVariable.'/', $token, $managementPageUrl);

        return url('/').$managementPageUrl;
    }


    /**
     * Find page with the specified component
     * @param  string $componentName component's name
     * @return CmsPage               page containg the component
     */
    public static function getPageWithComponent($componentName)
    {
        $theme = Theme::getActiveTheme();
        $pages = CmsPage::listInTheme($theme, true);
        foreach ($pages as $page) {
            if ($page->hasComponent($componentName)) {
                return $page;
            }
        }
    }

    public static function getComponentPropertiesFromPage($page, $componentName)
    {
        foreach ($page['settings']['components'] as $tmpComponentName => $componentProperties) {
            $exp_key = explode(' ', $tmpComponentName);
            if ($exp_key[0] === $componentName) {
                return $page['settings']['components'][$tmpComponentName];
            }
        }
    }
}
