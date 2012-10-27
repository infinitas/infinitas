Plugins making use of the content layout system in Infinitas will generally ship with some basic pre-defined layouts so that you can get up and running with minimal effort. These will however be quite generic and maybe not suitable in all cases. If you are looking to modify how some pages look read on.

Once you have opened up the [Contents plugin](/admin/contents) from the admin dashboard you should see a selection of options similar to the following. Click the layouts icon to view all the currently installed layouts.

[![](http://assets.infinitas-cms.org/docs/Core/Contents/layout.png "Modifying your content layouts")](http://assets.infinitas-cms.org/docs/Core/Contents/layout.png)


To modify an existling layout first find the appropriate one. For example you might be looking to customise the blog index page, or the CMS content page. Once you have the page that needs modification click its name or check the checkbox and click edit.

[![](http://assets.infinitas-cms.org/docs/Core/Contents/layout-index.png "Modifying your content layouts")](http://assets.infinitas-cms.org/docs/Core/Contents/layout-index.png)


You should now have a page open resembling the following. Before getting into editing the actual content, make sure you are farmiliar with the [various options available to the layouts](/infinitas\_docs/Themes/designer-creating-new-layouts)

[![](http://assets.infinitas-cms.org/docs/Core/Contents/layout-modify.png "Modifying your content layouts")](http://assets.infinitas-cms.org/docs/Core/Contents/layout-modify.png)

Infinitas makes use of the Mustache templating language so that it is easer for people that do not know php to build dynamic sites. See here more information about [Mustache in Infinitas](/infinitas\_docs/Templates/designer-theme-templating). See the documentation for the related plugin to get an idea of what variables would be available to your layout.

[![](http://assets.infinitas-cms.org/docs/Core/Contents/layout-mustache.png "Modifying your content layouts")](http://assets.infinitas-cms.org/docs/Core/Contents/layout-mustache.png)

The HTML section of the layout defines how content will be presented to the user. In order to customise your page you will need to know a little bit about [HTML](http://en.wikipedia.org/wiki/HTML). If you have a designer working on your site they should be able to do this quite easily for you.

[![](http://assets.infinitas-cms.org/docs/Core/Contents/layout-html.png "Modifying your content layouts")](http://assets.infinitas-cms.org/docs/Core/Contents/layout-html.png)

There is no hard limit to the amount or type of HTML that is entered into the box. It is even possible to insert some JavaScript although it is recommended to use JavaScript files for that as it can become difficult to maintain your site with embedded JS in various layouts.