At some point every site is faced with broken URL's. Boken URL's are a pain for users and can make a big impact on the SEO of a site. It does not matter how the broken URL's are formed, it only matters that they are taken care of.

> Usually if you are making big structural changes to a site it is better to make use of the functionality provided by your webserver to take care of broken URL's. This method can be done much earlier in the request which means far less overhead.

### Background

In the background Infinitas will silently record all broken URL's to the database, making them available to site admins to view and take action on at a later point. If the URL has not been accessed before it will be stored to the database without doing any redirection. If the URL has already been stored a counter will be updated making it possible to see which broken URL's are getting the most views (and possibly need additional fixing, possibly a more permanent solution like creating a route for it).

Once the administrator views these broken URL's, they will be able to configure a redirect address and status code for each URL they wish to fix.

Every broken URL that has a configured redirect URL will automatically redirect to the new page with the configured status code (temp / permanent). 

### Backend

Administrators are able to log into the backend of the site and view / manage broken URL's. These broken URL's can be fixed in the webmaster plugin by configuring a redirect URL which will automatically take users to the correct page.

Admins may also want to fix the URL by creating the correct route in the Routes plugin or possibly fixing the broken link where the problem originates (much easier if the problem is local).

Once a URL has been fixed it can be ignored which will hide it from general display in the backend while still doing all the automated redirections.

> Its important to note that this type of redirection fix will be causing additional requests to the server which can increase load and response time. This is ment to assist site admins resolve problems quickly while more permanent solutions are found.

If you prefer to avoid the overhead this functionality my case it can be disabled by setting `Webmaster.track_\404` to false in the Configs. 

### Alternative solutions

- Fix the incorrect URL, this is simple when the problem is local. If the problem is a broken link from a remote site try and contact them asking for the link to be fixed. Once the URL has been fixed the redirect can be removed.

- Create a route, The URL may be correct and only missing the correct configuration. If this is the case create the required routing configuration and remove the redirect.

- Moved / deleted content, it may pay to use regex in your webserver config to migrate as much as possible very early in the request. This can assist with load and response time for users.

- Short urls, to create short urls please see the ShortUrl docs. This should not be used for creating ad-hoc URL aliases.