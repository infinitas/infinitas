
The route manager is one of the more powerful features in Infinitas. It allows you to customise your site urls the way you want while also allowing you to customise your site based on the route that is used for rendering the page.

[![](http://assets.infinitas-cms.org/docs/Core/Routes/index.png "Route management")](http://assets.infinitas-cms.org/docs/Core/Routes/index.png)

When creating or editing Routes there are a number of options that need to be filled in. The page may look something like the following:

[![](http://assets.infinitas-cms.org/docs/Core/Routes/edit.png "Creating and editing Routes")](http://assets.infinitas-cms.org/docs/Core/Routes/edit.png)

### Route options
There are a number options here that need to be completed in order for your roures to work as expected. They are broken down as follows:

- `Name` This is a name that makes it easy to remeber what the route is for. There is _nothing specific_ required here but a good name will make it easy to know what the route is for.

- `Url` This is the url pattern used to match up user entered urls with your custom rules, see advanced routing for more information.

- `Prefix` This specifies what section of the site to link to, admin or frontend. Normally this is inherited from the current application state and is not required.

- `Route` This is how the url will map to the code and what it will load. You can specify exactly which part of the code will be run when a url match is made.

- `Values` These are custom values that will be sent to the code allowing you to specify some required params without having them show in the url.

- `Rules` These are [regular expressions](http://en.wikipedia.org/wiki/Regular_expression) that are used to help match the url and avoid false matches to an incorrect part of your site.

- `Pass` These are the matched values that are to be passed to the code. If you are matching a value that should be sent to the code being run.

- `Force` (front-end/back-end) If one of these are selected the rule will only match for that specific section of the site, for example only using the rule in the backend.

- `Active` You can use this to disable and enable Routes. Disabled Routes will not be used for matching urls to the sites content.

- `Layout` Change this to specify an alternative layout for the matched page. See more information about Themes and their configuration.

### Examples

You could set up your blog for example to accept urls such as `/blog/2012/01/your-title` with a url config like `/blog/:year/:month/:slug`. If you have a small site you could even use something simple like `/your-title` with a url like `/:slug`. 

The way you configure Routes should take into account the aim of the site and its estimated size in the future. Changing the urls of your content can cause havock for your [SEO](http://en.wikipedia.org/wiki/Search\_engine\_optimization) so thi should be though about before making your site live. 

> The Webmaster plugin can help with `404` pages and redirects.

It is important to note that the order in which the Routes are loaded makes a difference. Always set Routes from least greedy to most greedy.

For example if you created a route that had a url like `/*` which would catch anything, none of the other Routes below this one would ever be used. It is best to set Routes like `/custom/url` that has no variables first followed by Routes like `/custom/:variable` and finally `/custom/*`. This will help ensure the correct Routes are used when loading pages on your site.

### Advanced Routing

If you would like to use a few params as a single argument (possibly for a file system) you can use the `/\*\*` (double star) syntax. An example would be `/file/**`. The url  generated would look something like `/files/some/long/file/path` with the param passed to the controller as `some/long/file/path`

Routing can get pretty complex as it requires knowledge of [regex](http://en.wikipedia.org/wiki/Regular_expression). Knowing how the [CakePHP routes](http://book.cakephp.org/2.0/en/development/routing.html) work would not do any harm either.

Regular expressions could be used with the example above for the blog url. If you were making a url like `/blog/:year/:month/:slug` and wanted to be sure that only urls with valid dates were used you could add a rule for the parameteres. You should enter something along the following lines:

	{"year": "[0-9]{4}", "month": "[0-9]{2}"}

While that is not a perfect regular expression for year and months, you should see that using regular expressions can help filter urls to make sure requests will reach the correct controller.

### Route classes

Along with using regular expressions for url matching, some plugins make use of route classes. These classes contain code that will recieve the parameters after they have been correctly matched and then preform more checks to make sure that the request is valid.

Using the blog example from above a user might request the page `/blog/2012/01/your-title`. The route class would be able to check that there is in fact a blog post with the title `your-title` that was created in January 2012. If it was not a valid request the routing would continue trying to find a match in the other rouets and plugins or finally return an error message to the user.
