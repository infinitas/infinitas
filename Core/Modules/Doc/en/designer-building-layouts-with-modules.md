## Creating layouts

As with most things in life, there are many ways to skin a cat. The same is true for modules when it comes to building your site. Determining the best way to achieve a specific design comes from understanding your end goal and the amount of flexibility you require.

Generally speaking, the more flexibility the theme should offer the greater amount of back-end development and configuration is required. This normally adds to the complexities of management so all this should be considered before designing your site.

Normally a site consists of a number of distinct areas that content would be placed in. The most common examples being the horizontal header and/or footer of a site. Besides that, other popular sections found on sites would be a vertical left and/or right hand bar. More complex sites may have two or three different vertical bars, and some even contain two or more footers, sort of like this very site. An example of a common layout:

### Basic layout examples

Infinitas modules are great for building dynamic sites, allowing the end user to re-organise and alter the way the site displays based on a number of conditions. The design of the site and the theme will ultimately determine how much or how little can be modified and changed from the admin interface without having to resort to custom HTML in the content.

[![Blog layout](http://assets.infinitas-cms.org/docs/Core/Modules/layout-1c.png)](http://assets.infinitas-cms.org/docs/Core/Modules/layout-1c.png)
> A simple blog type layout using basic positions

[![No content](http://assets.infinitas-cms.org/docs/Core/Modules/layout-1a.png)](http://assets.infinitas-cms.org/docs/Core/Modules/layout-1a.png)

A more complex layout using multiple positions

[![No content](http://assets.infinitas-cms.org/docs/Core/Modules/layout-1b.png)](http://assets.infinitas-cms.org/docs/Core/Modules/layout-1b.png)
> A layout with no &quot;content&quot; position, generally used for landing pages and dashboards

## Advanced layout examples

Infinitas allows creation and positioning of content virtually anywhere within the layout. The positions used help determine where on the page certain content will be displayed allowing the end user to manage exactly where the content ends up and ultimately how the site looks and behaves. Below you can see how the exact same layout can be achieve in a number of ways.

[![Simple Layout](http://assets.infinitas-cms.org/docs/Core/Modules/layout-2a.png)](http://assets.infinitas-cms.org/docs/Core/Modules/layout-2a.png)
> A simple blog type layout using a header, footer and content

[![Complex Layout](http://assets.infinitas-cms.org/docs/Core/Modules/layout-2b.png)](http://assets.infinitas-cms.org/docs/Core/Modules/layout-2b.png)
> A more complex layout using an extra position to load modules

[![Advanced layout](http://assets.infinitas-cms.org/docs/Core/Modules/layout-2c.png)](http://assets.infinitas-cms.org/docs/Core/Modules/layout-2c.png)
> Advanced layout using multiple positions for finder control

## Using modules

The names of the module positions are not important as far as functionality goes, but using the positions that make sense allow for easy identification in the back-end. A poor example would be using the `header` position in the actual `footer` of the site. This will only lead to confusion and annoyed administrators. It is best to use the most relative descriptive name available or create a custom name if required.

Module positions such as `header`, `footer` or `custom1` are only placeholders. Infinitas uses these placeholders to load various bits of content into.

The content can be virtually anything from a list of popular posts, to latest products. From the back-end modules can be created using static HTML or even mustache template allowing complete customisation of the site. You could even go so far as to add positions within positions, although that could lead do a site that is overly complex to administer.

It is important to think of where or how the content for your modules will come from. Most plugins will have certain modules available, for example the Blog plugin provides modules for latest content, popular posts and posts by date. These modules are already programmed to fetch data from the database and build the HTML markup to display with in the module. Unless you are building modules from static HTML markup there may be a need for writing code that fetches the data for the module.

## Configuring modules

Dynamic modules that require content from the database generally need to be programmed before being able to be used, making the data available for the module to be rendered.

Modules and even module positions can be re-used multiple times on a single page.

Modules can normally be configured depending on how it has been created. This may allow using the same module a number of time, each showing slightly different content. An example would be a module that shows the latest products in a shopping cart. The same module could be used over and over, with each one specifying a category. The final output would be a number of modules that list new products per category. Another example using a the CMS plugins would be a module that loads an introduction for an article based on the category. This would again create a list of new articles from each category.

For more information on how modules are loaded and when see [here](/infinitas_docs/Modules/developer-modules).