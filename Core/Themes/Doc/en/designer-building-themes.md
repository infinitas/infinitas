Building a theme for Infinitas is very similar to building [themes for CakePHP](http://book.cakephp.org/2.0/en/views/themes.html). One noticeable difference is that within Infinitas themes need to be installed, and as such require a config file similar to the plugins.

Besides the config file which allows installing, the functionality of themes is exactly the same. From a theme you are able to override any view file for any plugin, create unlimited amount of layout files and completely customise how the site looks.

### Example themes

To get started you might want to look at how the [default theme](https://github.com/Infinitas-Themes/Infinitas) or [basic theme](https://github.com/Infinitas-Themes/InfinitasBaseTheme) is structured. The `default` theme is what is installed by default for new sites. The `basic` theme is an example repository showing structure and more detailed information about every section of a theme.

### Assets

Every theme can (and should) contain its own set of assets, that is `images`, `CSS`, `JavaScript` and so on. If you are building a theme and would like to use jQuery, jQuery UI or bootstrap you should look at the Assets plugin as it contains up to date versions of these assets.

Using the Assets plugin can help in a number of ways, for example:

- A site using multiple themes using the Assets plugin will be cached after the first page load.
- Less code to maintain, let Infinitas handle the updates.
- Built in tools, such as the `less` compiler to aid development.

### Sections

There are currently three main sections of any site to theme. For each section you can have any number of layouts available for use.

#### Frontend `common`

This is the most common use for Themes, customising the look and feel of the site.

#### Admin `Uncommon`

If you are building an application and would like a white label backend or customised to the clients company for example, creating a custom admin theme is the way to go.

#### Installer `Uncommon`

A recent update to the theme integration and Installer refactoring has made it possible to fully customise the theme for the installation pages. This is great for people that are building an application for distribution and would like to give their users a customised installer.

### Layouts

There is no limit to the number of layouts your theme could contain. Generally sites will have anything from 1 - 5 different layouts for different sections of the site. For example you might have the following basic setup:

#### Basic site / Personal blog

- `Front`: all pages have a similar look Shared layout means things like `headers`, `footers` and `side bars` will be common though out. (You can still customise the site dynamically though the Modules plugin)

#### Company site

- `Dashboard`: a landing page that has no `side bars`, centre `carousel` and some fixed text.
- `Front`: General Blog and / or Cms pages, with mixed `side bars`, contact details in the footer etc.
- `Contact`: Similar to the dashboard, but even less `noise`
- `Projects`: Trimmed down to a single `side bar`, possibly using different css entirely from other layouts.

### Tips

> Themes should be installed into `APP/View/Themed`. If you have a theme as part of your company plugin you can always `symlink` the theme into the correct folder. This way you can still manage it with `git` as part of your plugin.

> It is possible to build a complex site with one single layout, but its messy. Nested if / else and checks all over. Break your site up into distinct layouts that are easy to manage.

> Put shared code such as headers or footers in elements and load the element in each layout as needed. This again makes the theme [DRY](http://en.wikipedia.org/wiki/Don't_repeat_yourself) and easier to manage as you do not have duplicated code all over.

> Use the built in assets for things like [jQuery](http://jquery.com/) and [Twitter bootstrap](http://twitter.github.com/bootstrap) to help with cache and maintenance.