The Newsletter plugin provides functionality for creating email lists that users can subscribe to and later on receive periodic emails related to their topics of choice. There are a number of sections to the plugin highlighted below:

### Subscribers

These are the users that have subscribed to the emails on your site. There are various states that a subscription can be in such as `pending`, `active` and `canceled`. Users can be manually subscribed to lists by site admins in the backend or through the frontend by them selves.

### Subscriptions

Once a user has subscribed to the site emails they will be able to manage their subscriptions. Generally a site will have more than one email list such as `latest news`, `weekly activity` and / or `new products`. Subscriptions allow users to select which emails they want and which they don't want.

### Campaigns

These are the topics or newsletters that the users would subscribe to. When creating a newsletter in the backend the site administrator will assign a campaign which will determine who gets the email.

### Templates

Newsletter templates are the base for your email. This is defined as the `header` and `footer` of the email and what the body of the email will be inserted into. The templates are similar to the **layouts** used to build a website.

### Newsletters

The newsletters is the email content that will be sent to users. Generally these are built using mustache templates similar to what is used in site content for inserting dynamic information into the email. Newsletters also allow the site admin to specify other details such as the subject and the reply to address. Each newsletter is linked to a campaign which allows sending the correct email to the correct subscriber.