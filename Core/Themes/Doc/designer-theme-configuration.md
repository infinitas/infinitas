Some Themes will only contain a single layout file. If this is the case there is not much to the configuration besides activating or deactivating the theme.

To access the [theme management](/admin/themes) screen, from the admin dashboard click the configuration menu on the top navigation bar. Once the page has loaded you will have something like the following.

[![](http://assets.infinitas-cms.org/docs/Core/Themes/access.png "Access theme management")](http://assets.infinitas-cms.org/docs/Core/Themes/access.png)

Click the theme icon to open up your sites themes.

[![](http://assets.infinitas-cms.org/docs/Core/Themes/index.png "Theme manager")](http://assets.infinitas-cms.org/docs/Core/Themes/index.png)

As with most other plug-ins within Infinitas, you are able to preform a number of actions. The most important ones to know about for Themes is install and toggle. For more information about installing themes [click here](/infinitas\_docs/Themes/installing-new-themes).

Infinitas tracks which theme is the default, this is the theme that will be used throughout the site should there be no overloading by any other configurations. To enable or disable a theme, select the check box next to the them you want to change and then click the toggle button.

[![](http://assets.infinitas-cms.org/docs/Core/Themes/toggle.png "Setting the default theme")](http://assets.infinitas-cms.org/docs/Core/Themes/toggle.png)

> Only one theme can be the default and there should always be a default selected. If there was no default theme available there is a good chance none of your content will display on your site.

By default Infinitas will use a layout called `front` which would be located within the themes `Layout` directory (Eg: `/YourPlugin/View/Themed/your_theme/`) and named `layout.ctp`. If there is more than one layout file available you may wish to change the default to something else. Here it has been set to custom_default which maps to the themes layout file Layouts/custom_default.ctp

[![](http://assets.infinitas-cms.org/docs/Core/Themes/default.png "Changing the default layout")](http://assets.infinitas-cms.org/docs/Core/Themes/default.png)

There are a number of ways to fine tune the look and feel of your site. Normally different pages may have slightly different layouts so to make customising your sites look and feel Infinitas allows you to change the layout in various places. We have covered the default layout so lets see how it can be customised.

The first place that Infinitas will look for a theme override is in the Routes. When you specify custom url structures one of the options is which theme will be used for the page that is generated. Opening up the [route manager](/admin/routes) you will see it specifies what theme and layout will be used.

[![](http://assets.infinitas-cms.org/docs/Core/Themes/route-override.png "Layout override based on routes")](http://assets.infinitas-cms.org/docs/Core/Themes/route-override.png)

If you are making use of content layouts Infinitas will look for overrides is there too. When you create the content layout you are given the option to set the layout. If nothing has been selected the default for the theme will be used. Opening up a layout in the content layouts will give you the following options.

[![](http://assets.infinitas-cms.org/docs/Core/Themes/layout-override.png "Override based on content layout")](http://assets.infinitas-cms.org/docs/Core/Themes/layout-override.png)

You may wish to use an entirely different theme for a section of the site all together. In this case you could opt to select just the theme and leave the layout blank which will use the themes default layout.

[![](http://assets.infinitas-cms.org/docs/Core/Themes/layout-override-default.png "Changing the theme dynamically")](http://assets.infinitas-cms.org/docs/Core/Themes/layout-override-default.png)

Finally depending on the plugins you are using there may be options to configure the layout per record. For example you may be using a shopping cart plugin that allows overloading of the theme layout per product. If this option is available you should see similar configuration options when creating or editing the record.

If for some reason a layout does not work as expected it is possible that the plugin you are using has `hard coded` the layout to be used. Although it is **not** recomended that developers hard code these configurations it may be required to avoid some sort of error that would happen if a specific layout was not used.
