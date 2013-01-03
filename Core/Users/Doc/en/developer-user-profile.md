You can add custom content to a user profile from any plugin through the events system. To get a good idea at what is going on have a look at some of the other plugins that already do this such as the Shop or Twitter plugin.

The information added can be virtually anything, but is generally best left as simple data with links to more complete versions. For example the Shop plugin displays that latest few orders with a link to view all.

### Event

You need to create the event in your plugin that is triggered by the profile page. The format of the data to return is an array with a `title` and `content` values.

	public function onUserProfile(Event $Event, array $user) {
		return array(
			'title' => 'My awesome content',
			'content' => '<p>Some text here</p>'
		);
	}

It is important to remember that the default is to show this data in an [accordion](http://twitter.github.com/bootstrap/javascript.html#collapse). If this is changed it will most likely not be much more complex that this either. It is a good idea to keep the content simple.

If your plugin is more complex and requires a number of different sections you can return a nested array with the same format. This will generate two secions on the profile page, but there is no limit.

	public function onUserProfile(Event $Event, array $user) {
		return array(
			array(
				'title' => 'Heading 1',
				'content' => '<p>Some text here</p>'
			),
			array(
				'title' => 'Heading 2',
				'content' => '<p>Other awesome text</p>'
			)
		);
	}

### Using helpers

Part of the Events data that is passed in is the Handler, and as this is triggered from the view that is what the handler is. You will have access to all the helpers that are normally available.

	public function onUserProfile(Event $Event, array $user) {
		return array(
			'title' => 'My awesome content',
			'content' => $Event->Handler->\_View->Html->tag('p', 'Some text here')
		);
	}

Sometimes its easier to shorten the helper usage. As PHP passes objects as references this should not hurt your page loads too much.

	$Html = $Event->Handler->\_View->Html;
	return 	array(
		'title' => 'My awesome content',
		'content' => $Html->tag('p', 'Some text here')
	);

### User data

The second param passed to the event, `$user`, is the logged in users data. This is exactly what you would expect from `User::find('first')`.