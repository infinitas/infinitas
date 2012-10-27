Content layouts are dynamic parts of a page that usually contain the main content displayed to the user. In the case of blogging site, the blog post would be created using the blogs content layout.

For the most part all plug-ins will have their own layouts to use and would not normally be interchangeable due to the difference in data available in each. You can however use them to display pages differently depending on certain criteria.

[![](http://assets.infinitas-cms.org/docs/Core/Contents/layout-index.png "Creating new layouts")](http://assets.infinitas-cms.org/docs/Core/Contents/layout-index.png)

There is a number of reasons you would need to create a new layout. Possibly you are working on a new page layout for your site or the plug-in you use did not come with any pre made layouts.

To get started creating a new content layout, open up the core Contents plug-in and navigate to the layouts section. You should be presented with a list of layouts and some action buttons near the top of the screen. Clicking add will take you to a page where you can build your new layout.

[![](http://assets.infinitas-cms.org/docs/Core/Contents/layout-add.png "Creating new layouts")](http://assets.infinitas-cms.org/docs/Core/Contents/layout-add.png)


### Configuration

There are a number of different ways to configure your layouts depending on what you need your pages to do. Common differences include index pages showing multiple records (new produts or a category in your blog) and view pages displaying a single record (a single product or a single post). See the options below for more details.

#### Route
	
This is which content the layout will be used for. Start by selecting the plug-in you are creating the layout for and then the data model that will be used.

#### Auto Load
	
For pages where you are viewing a single record (e.g.: a single blog post, content page or product for example) would not generally use this option as most plug-ins will give this option when creating the record. If you are building the layout for something like a homepage or index of records you would need to fill out the auto load field containing the method where this is loaded. Generally for index pages this would be `index`.

#### Layout
	
specifies in which theme layouts it would be loaded. Leaving this option blank would mean it would be the default regardless of the theme. Setting a theme and layout would mean the content layout would only be loaded when that specific theme has been used.


#### Name
	
this is just a general name used to identify the layout in the back-end. You should provide a name that is easy to identify and descriptive, to keep management of your site simple. Good names include `custom blog index` or `generic blog view`. Bad names would be `layout 1`, `layout 2` or `new layout`.


[![](http://assets.infinitas-cms.org/docs/Core/Contents/layout-example.png "Layout example")](http://assets.infinitas-cms.org/docs/Core/Contents/layout-example.png)

#### Your custom template

- Once you have configured how the layout works you can begin adding your layout mark-up and mustache templating. It is generally recommended to keep JavaScript and CSS in proper asset files in the themes and plug-ins, although you are able to use them within the layouts.

- As an example to creating a layout for a blog index, you would need to run through each of the different records available and display the information. This is done using the Mustache templating language to create a set of place holders where data will be rendered into.

- As you do not know what exactly could be available and to reduce the amount of code need you can use Mustache to loop through the records, meaning you only need to define one set of place holders. As you can see from the simple example there is very little html used in this example template, even thou in reality it could be displaying hundreds of posts, or thousands of lines of HTML mark-up.

- Each of the different blocks represents one of the place holders defined within the { and } markers, while each different colour indicates a different record.

- Its a good idea to keep the layouts clean and simple to help keep your site easy to maintain. Modules are a great way for including duplicated content blocks within your site and can easily be included within layout to build extremely complex and dynamic pages.