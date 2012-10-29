#### Background

The plugin uses a simple `base 62` encoding system (that is integers are converted to `A-Z`, `a-z` and `0-9`) to convert the integer id of the url that is shortend into a code. For example the initial short urls will not look any different to the ids of the row saved:

- row 1: `/s/1`
- row 2: `/s/2`
- row 3: `/s/3`

And so on. If gets more interesting as the numbers get larger, for example:

- row 10: `/s/a`
- row 11: `/s/b`
- row 12: `/s/c`
- ...
- row 61: `/s/Y`
- row 62: `/s/Z`
- row 63: `/s/11`
- row 64: `/s/12`

As you can see the number of characters required to represent `base 10` using `base 62`, Infact you would need `238,328` urls before the short url would reach 4 characters long or `/s/1000` and `56,800,235,583` urls to reach the last 6 character long url `/s/ZZZZZZ`

Given `n` as the number of characters in the short url, the number of urls required to reach that level is

	(62^n)-1

#### Creating urls

To create a short url there are a few options available. The easiest method is using the Events system. You would trigger an event for `getShortUrl`. The url shortening can be done for both `string` and `array` urls.

	// In models, controllers and views
	$this->Event->trigger('getShortUrl', array(
		'url' => '/your/url'
	));

	// In models, controllers and views
	$this->Event->trigger('getShortUrl', array(
		'url' => array(
			'controller' => 'example',
			'action' => 'example'
		)
	));

The other option is to use the model directly. 

	$code = ClassRegistry::init('ShortUrls.ShortUrl')->shorten('/your/url');

	$code = ClassRegistry::init('ShortUrls.ShortUrl')->shorten(array(
		'controller' => 'example',
		'action' => 'example'
	));

#### Decoding urls

If the url like `/s/{code}` is requested the ShortUrls plugin will handle everything. If you needed to convert a code into a url without doing the redirect you can do the following

	$url = ClassRegistry::init('ShortUrls.ShortUrl')->getUrl($code);
