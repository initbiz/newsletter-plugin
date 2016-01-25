# Newsletter plugin
- [Introduction](#introduction)
- [Features](#features)
    - [Translations](#translations)
    - [Mail templates](#mailtemplates)
- [Frontend components](#frontendcomponents)
    - [NewsletterForm](#newsletterform)
    - [NewsletterConfirm](#newsletterconfirm)
        - [Usage](#usage)
- [Future plans](#futureplans)

<a name="introduction"></a>
## Introduction
The plugin helps sending e-mails to subscribers.

The main purpose is to send e-mails only to subscribers that confirmed their addresses and who accepted the text next to the first checkbox (usually regulations).

**\$2 of \$5 price will go to help fund OctoberCMS project.**

<a name="features"></a>
## Features
Subscribers can confirm they want to get news on e-mail and sign out from getting newsletter by clicking the link stored in `newsletterLink` variable.

Newsletter administrator **cannot** add or delete subscribers.

Newsletter administrator can save message without sending it.

Administrator can customize text displayed next to checkboxes (regulations and agreement).

<a name="translations"></a>
### Translations
Plugin supports translations for all elements (there is no hardcoded frontend contents) including AJAX responses and displayed errors.

Currently it supports two languages:

 - pl - Polski
 - en - English

<a name="mailtemplates"></a>
### Mail templates
There are two mail templates you will want to customize:

 - `inibiz.newsletter::mail.subscription` which is sent to those who want to became a subscriber
 - `initbiz.newsletter::mail.message` which is sent to subscribers

In `subscription` mail template you can use `{{activationLink}}` variable.

In `message` mail template you can use:

 - `{{title}}` - Title of message
 - `{{content}}` - Content of message
 - `{{ newsletterLink }}` - link for subscribers to sign out from newsletter

Actually `newsletterlink` and `activationLink` is the same link because of single component which handles both of actions (confirming and signing out).

<a name="frontendcomponents"></a>
## How-to (frontend components)
<a name="newsletterform"></a>
### NewsletterForm
This component is responsible for displaying form for people who want to became a subscriber.
It will display e-mail text field, submit button and two checkboxes:

 - `requiredCheckbox`
 - `optionalCheckbox`

You can customize content displayed next to the checkboxes in `Settings` -> `Newsletter` -> `Newsletter`. You can use HTML there.

<a name="newsletterconfirm"></a>
### NewsletterConfirm
This component is responsible for getting e-mail address and token from URL and display two things:

 - *Thank you for registering* message right after e-mail address confirmation
 - *Sign out* button after visiting the page next time

<a name="usage"></a>
#### Usage

 1. Create page we want users to use to manage their subscription (for example `manage-newsletter`) and set URL to `/manage-newsletter/:email/:token`
 2. Embed component `NewsletterConfirm` on page
 3. Go to `Settings` -> `Newsletter` -> `Newsletter` and set your newly created `manage-newsletter` page in select page list.

<a name="futureplans"></a>
## Future plans
 - Adding and removing checkboxes next to the subscribe-form in NewsletterForm component
 - Grouping subscribers and sending mails to selected groups only

