The comments plugin generates a number of emails as comments are made on content. It makes use of the Newsletter plugin to send these emails which are easy to modify in the backend.

The emails make use of the same templating system that is available to the frontend views. This allows you to customise the emails with dynamic content. Each email will have the basic details of the user being emailed and the details of the comment.

> As sending emails can take some time it is recommended to install and use the InfinitasJobs plugin so that emails will be queued. If this plugin is detected and active emails will not be sent directly, but instead will be sent to a queue for offline processing. This will make your site **much faster** while emails will have a small delay.

### Emails

#### comments-new-admin

When a new comment is created all the administrators on the site will receive and email which contains basic information about the comment so they can decide if there is any moderation required.

#### comments-new-user

After a comment is made, any people that have previously commented on the same thread will be notified, excluding the person that made the current comment.

### Variables

When addressing the user it is recommended to use `User.name` as this field will be populated with something. Some users might not have a `prefered\_name` saved so `name` will fall back to `full\_name` or `username`.

> If you are building a public site, do not add the commentors email to the `comments-new-user` email as you might upset some people.

#### User details

The details of the person being emailed

	'User.id' => 00000000-0000-0000-0000-000000000000
	'User.email' => user@exmaple
	'User.prefered\_name' => user
	'User.username' => username
	'User.name' => user

The details of the comment that was made

	'Comment.username' => commentor
	'Comment.email' => commentor@example.com
	'Comment.comment' => The comment text
	'Comment.points' => 3
	'Comment.status' => approved
	'Comment.active' => 1
	'Comment.url' => http://site.com/some/post

- `points` is the number of points the comment scored in the spam test
- `status` is a textual representation of the status (pending, active, spam)
- `url` is a link to the post that was commented on
- `active` defines if the comment is visible to other users or not

	'Comment.id' => 00000000-0000-0000-0000-000000000000
	'Comment.user_id' => 00000000-0000-0000-0000-000000000000
	'Comment.mx\_record' => 1
	'Comment.foreign_id' => 00000000-0000-0000-0000-000000000000
	'Comment.ip\_address' => 192.168.1.78
	'Comment.class' => Plugin.ModelName
	'Comment.created' => 2013-01-19 15:50:43

- `mx\_record` indicates that the domain is valid or not
- `ip\_addresss` is the IP address of the user that made the comment,
- `user\_id` will be null if **not registered**
- `class` and `foreign_id` is how the comment relates to a particular row