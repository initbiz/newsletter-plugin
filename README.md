## Newsletter plugin

### Introduction
The plugin helps with sending newsletter.

The basic plugin's use case is as follows:
1. Visitor on page sees a subscribe-to-our-newsletter form
1. Enters his/her e-mail address and gets the information to confirm the address after clicking confirmation link in message
1. Right now admin can see visitor as a subscriber and can send an e-mail to the visitor
1. If user do not like the newsletter he will get an unsubscribe link in every message

What is more:
* Admin can manage checkboxes rendered with form so that there are required and optional checkboxes:
  * Required have to be checked by visitor (in most cases this will be accepting regulations or policies),
  * Optional do not have to be checked. Using optional checkboxes we can give subscribers choice what type of messages they want to recieve (our offer, news or just message categories).
* User can manage the message categories visiting manage newsletter page (and seeing optional checkboxes)
* Admin can specify if he/she wants to send the message to all users, or just those who accepted the particular optional checkbox
* Admin can save message without sending it

## Documentation

### Usage
1. Create page for managing newsletter options by subscribers so that it has `:email` and `:token` variables (for example `manage-newsletter` with `/manage-newsletter/:email/:token` URL).
1. Embed component `NewsletterConfirm` on page
1. Go to backend settings -> Newsletter and set your newly created `manage-newsletter` page in select page list.
1. Go to backend Newsletter -> Checkboxes and add checkboxes as your business requires
1. Embed component `NewsletterForm` on page that you want to have form rendered on
1. Do not forget to configure e-mail settings in your backend settings

### Translations
Plugin supports translations for all elements (there is no hardcoded frontend contents) including AJAX responses and displayed errors.

Currently it supports two languages:

 - pl - Polski
 - en - English

### Mail templates
There are two mail templates you can to customize:

 - `inibiz.newsletter::mail.subscription` which is sent to those who want to became a subscriber
 - `initbiz.newsletter::mail.message` which is sent to subscribers

In `subscription` mail template you can use `{{activationLink}}` variable.

In `message` mail template you can use:

 - `{{title}}` - Title of message
 - `{{content}}` - Content of message
 - `{{ newsletterLink }}` - link for subscribers to sign out from newsletter

`newsletterlink` and `activationLink` is the same link because of single component which handles both of actions (confirming and signing out).
