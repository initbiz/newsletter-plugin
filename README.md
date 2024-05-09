Newsletter plugin
===

![Newsletter plugin](docs/newsletter.png)

### Introduction

The plugin helps with sending newsletter.

The basic use case is as follows:
1. Visitor on page sees a subscribe-to-our-newsletter form
1. Enters his/her e-mail address and ticks checkbox that he/she agree with
1. He/she receives message to confirm the e-mail address
1. From now on, admin can send an e-mail to him/her
1. If visitor do not like the newsletter he/she can unsubscribe using link in included in every message

What is more:
* Admin can manage checkboxes rendered with form so that there are required and optional checkboxes:
  * Required have to be checked by visitor (in most cases this will be accepting regulations or policies),
  * Optional do not have to be checked. Using optional checkboxes we can give subscribers choice what type of messages they want to recieve (our offer, news or just message categories).
* Visitor can manage the message categories visiting manage newsletter page (and seeing optional checkboxes)
* Admin can specify if he/she wants to send the message to all users, or just those who accepted the particular optional checkbox
* Admin can save message without sending it

> **The plugin makes you GDPR ready and is fully translatable (see Documentation).**

## Tech documentation

### Usage

1. Create page for managing newsletter options by subscribers so that it has `:email` and `:token` variables (for example `manage-newsletter` with `/manage-newsletter/:email/:token` URL). Of course those variables can be changed.
1. Embed component `NewsletterConfirm` on exact one CMS page (Newsletter plugin will automatically look for page that has the component and cache it for 10 minutes)
1. Go to backend Newsletter -> Checkboxes and add checkboxes as your business requires
1. Embed component `NewsletterForm` on page that you want to have form rendered on (landing page or just footer partial)

> As of version 1.1.0, `:email` parameter is optional in the newsletter management page.

### Integrations

The plugin makes it easy to integrate with other sending e-mails services like MailChimp or MailerLite.

Out of the box only MailerLite is supported.

To integrate with other service, you can use one the the following events:

- `initbiz.newsletter.subscriberCreate ($subscriber)`
- `initbiz.newsletter.subscriberSave ($subscriber)`
- `initbiz.newsletter.subscriberDelete ($subscriber)`
- `initbiz.newsletter.tagCreate ($tag)`
- `initbiz.newsletter.tagSave ($tag)`
- `initbiz.newsletter.tagDelete ($tag)`
- `initbiz.newsletter.subscriberCheckboxesAttached ($subscriber, $checkboxes)`
- `initbiz.newsletter.subscriberCheckboxesDetached ($subscriber, $checkboxes)`
- `initbiz.newsletter.subscriberTagsAttached ($subscriber, $tags)`
- `initbiz.newsletter.subscriberTagsDetached ($subscriber, $tags)`
