The ShortUrls plugin provides administrators of an Infinitas powered site and developers of Infinitas plugins a way to create short urls that are easy to share in social media sites such as Twitter and Facebook.

A route is configured to accept codes at two specific urls:

- `/s/{code}`: redirect to the url
- `/s/p/{code}`: preview the url that would be redirected to.

Once a valid code is received depending on the url the user would be redirected to long version of the url. 

For example you might have a url that looks like `http://example.com/some/long/url/to/show.html`. Once you save this url it is given an `id`, the first one would be `1`. The shortened url would then be `http://example.com/s/1`. Besides having a short url that is easy to share, it also gives you controll over the redirects that you would not have using other url shortening services.

You can [create and manage](/infinitas\_docs/ShortUrls/create-short-urls) ShortUrls from the backend . Developers are also able to create the short urls [programmatically](/infinitas\_docs/ShortUrls/developer-create-short-urls)