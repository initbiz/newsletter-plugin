<?php namespace Initbiz\Newsletter\Classes;

use Mail;
use Initbiz\Newsletter\Models\Settings;

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
        return url('/') . '/' . Settings::get('managementpage') . '/' . $email . '/' . $token;
    }
}
