The Webmaster plugin handles day to day site management including things such as managing the [robots.txt](http://en.wikipedia.org/wiki/Robots\_exclusion\_standard) file, general [SEO](http://en.wikipedia.org/wiki/Search\_engine\_optimization) and related.

It also allows creation of [sitemaps](http://en.wikipedia.org/wiki/Site\_map) to aid robots crawling the site. This is done through the Events system and allows any plugin to hook into the sitemap building process so that it may be included in the overall sitemap.

> If you are building an _internal_ application that will not be visited by the general public, this plugin may be of little use. 

### Automation

Nobody wants to have to log in every few days to update the sitemap or rebuild it when there is new data. With the help of the Crons plugin, sitemap generations can be automated. Nothing aditional is besides setting up the Crons is required to have the site maps automatically generated. See the Crons documentation for more information.

### Roadmap

There are plans to add code that will catch `404` errors to the Webmaster plugin. This will allow users to see what URL's have been requested that resulted in a [404 error](http://en.wikipedia.org/wiki/HTTP\_404) and create a redirect for future visitors. This will also make managing changing urls a bit easier as site admins will be able to create redirects directly in the backend before a `404` error is triggered.