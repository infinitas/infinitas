[![](http://assets.infinitas-cms.org/docs/Core/Modules/index.png)](http://assets.infinitas-cms.org/docs/Core/Modules/index.png)

Majority of the time Modules create blocks of `HTML` markup loaded into a specific positions on your site. The positions are governed by what is available in the Themes available on your site.
Even though you can create your own positions in the back-end and assign Modules to those positions, they will only be displayed if the layout you are using [loads the Modules](/infinitas\_docs/Modules/designer-Modules-for-designers).

Besides the position you choose to load a module in, you are also able to configure the order the Modules are loaded in. This allows you to finely tune exactly where each block of markup would appear on your site

[![](http://assets.infinitas-cms.org/docs/Core/Modules/positions.png)](http://assets.infinitas-cms.org/docs/Core/Modules/positions.png)

When you create or edit a module there are a number of options available that will determine the final output. The most obvious one is the actual module that is loaded. This is done by first selecting the plug-in to load from and then picking an available module.
Some Modules have configuration options that are currently set using JSON.

[![](http://assets.infinitas-cms.org/docs/Core/Modules/form-details.png)](http://assets.infinitas-cms.org/docs/Core/Modules/form-details.png)

The next important detail is where the Modules will be shown and who will be able to see them. Modules are also linked to themes and Routes to have fine control over when they are displayed.
These options allow you to drastically change the appearance of a page with minimal effort.

[![](http://assets.infinitas-cms.org/docs/Core/Modules/form-where.png)](http://assets.infinitas-cms.org/docs/Core/Modules/form-where.png)

#### Group

The group option sets who is allowed to see a module. You may have different types of users logging into your site and want to show different Modules based on that criteria.
An example would be in a Shop where you have `general public` and `wholesalers` as your user types. You could then have the wholesaler specific Modules and general public specific Modules loaded in the same position but only displaying to the relevant group of users.

#### Position

There are a number of default positions included in the base instal of Infinitas which normally covers most usage cases. If additional positions are required by a theme they will be created as part of the installation of that theme.

#### Theme

Specifying a theme on the module means that the module will only be loaded for a specific theme. For example you may have a blog and a shopping cart, both with individual themes. Should you wish to only show a specific module on any pages related to the shopping cart you would select its theme. You can then ignore the routing configuration as even if the URL was /blog/some-post it would not be loaded as it is the wrong theme.

#### Route

Leaving all the Routes unchecked means that the module will be loaded for all Routes (unless something like a theme is selected). If you only want to module to show up on a few pages you are able to use the route selections here.
A common example is specifying that a module would only be shown on the home page, in this case you would select the route for your home page only. Another common situation is where you would only like the module to show on index pages (pages that list rows of data), in which case you would select all the Routes that displayed index pages.

#### Combine the options

For more complex configurations it is possible to configure a module to only show based on all the specified conditions. For example, you may only want to show the module if the user belongs to the group `customers` on view pages (eg: showing a single post or product etc) when the theme used is `my theme`.

The final screen for Modules covers some basic information about the author, copyright information and where updates can be found.

[![](http://assets.infinitas-cms.org/docs/Core/Modules/form-creator.png)](http://assets.infinitas-cms.org/docs/Core/Modules/form-creator.png)

### Loading Modules from content

Sometimes you will need to load a module directly without intervention from the theme. This may include showing a block of markup in your content pages or even possibly in an email newsletter being sent out.

Infinitas makes this possible through the use of a special markup that will tell the core you require a module to be loaded. There are a few different forms depending on what exactly you are trying to load.
The markup used for this is done with square brackets. As an example (with different brackets to avoid it being rendered), (command:PluginName.file) or more advanced (command:PluginName.file#params). Square brackets are avoided here as they would be rendered.

Loading Modules incorrectly on a production site will not output anything at all. It is important to test with debug on or to keep an eye on your log files should anything not work as expected.