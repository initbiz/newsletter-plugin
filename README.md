Newsletter plugin
===

![Newsletter plugin](docs/newsletter.png)

### Introduction

The plugin helps with managing audience. Right now it has out of the box integration with MailerLite but adding a new one is pretty straightforward from any other plugin.

In the plugin we have Subscribers, Tags, Checkboxes, and Messages.

- Subscribers have many built in fields but they can also have additional fields added.
- Tags are for grouping people. Every subscriber can have many tags.
- Checkboxes are "rules" that people accept on signing in. They may be required, or not.
- You can also send messages directly from the plugin to your audience. It's not common these days because you probably would want to use an external service for that.

### Usage

1. Create page for the subscribers to manage their subscription - component `NewsletterConfirm` is automatically confirming and renders form for managing the subscription.
1. Add `Form` component to every place that you want your subscribers to sign in

> As of version 1.1.0, `:email` parameter is optional in the newsletter management page.

## Tech documentation

### Integrations

The plugin makes it easy to integrate with other sending e-mails services like MailChimp or MailerLite.

Out of the box, for now, only MailerLite is supported.

To integrate with other service, you can use one the the following events:

- `initbiz.newsletter.subscriberCreate ($subscriber)`
- `initbiz.newsletter.subscriberUpdate ($subscriber)`
- `initbiz.newsletter.subscriberDelete ($subscriber)`
- `initbiz.newsletter.tagCreate ($tag)`
- `initbiz.newsletter.tagUpdate ($tag)`
- `initbiz.newsletter.tagDelete ($tag)`
- `initbiz.newsletter.subscriberCheckboxesAttached ($subscriber, $checkboxes)`
- `initbiz.newsletter.subscriberCheckboxesDetached ($subscriber, $checkboxes)`
- `initbiz.newsletter.subscriberTagsAttached ($subscriber, $tags)`
- `initbiz.newsletter.subscriberTagsDetached ($subscriber, $tags)`

