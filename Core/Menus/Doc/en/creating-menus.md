
Menus are made up of a Menu with one or more MenuItems. The Menu holds a collection of MenuItems that can be easily accessed by the Menu name. This is used to easily render a menu in a specific position.

> The menu items are able to be nested which allows creating dropdown menus. There is no direct limit on how deep a menu can be nested, but too many levels can cause usability issues.

### Creation

First step is creating the Menu if you have not already. This can be done in the [menu manager](/admin/menus). A Menu is simply a name which will later have one or more MenuItems linked to it.

Once you had a Menu created you can start adding MenuItems to it. You can do this by clicking the Menu in the admin backend or going directly to the [menu items](/admin/menus/menu_items).

A menu item has quite a few options, most importantly is the `name` and the `url` it points to. The `url` can be made up of either a fixed full url such as `http://google.com/`, a fixed relative url such as `/login` or using the dropdowns to select the `plugin`, `contrller`, `action` and other details. Using the later option may seem like a little bit more work up front but has large benifits.

#### URL type

MenuItems when created using the dynamic urls are linked to Routes. This means that you can controll the actual url used through the Routes plugin without having to adjust your menus if you need to change the `urls`. 

For example, if you were building a menu with a link to the login page you could just enter `/login` which would work with the default Infinitas configuration. However, if at a later point you decided that the url should actually be `/access` you would need to adjust the menu accordingly.

If you had specified the plugin, contrller and action as `users`, `users` and `login` respectivly there would be no change required as the URL would automatically be created as `/access` via the Routes plugin.

#### Parent

When creating the `MenuItem` you will need to select which Menu the item belongs to. One you have selected the Menu you will also be able to pick where abouts in the menu the new (or edited) link will fit. If you do not need a nested menu or it is a top level link you should select `ROOT`.

#### Active

Only active items will be fetched from the database when the menu is being fetched for display. This option allows you to create menu items that may be `turned on` at a later stage, or disabled should they not be accessed.

> Disabling a menu item will not stop a user from accessing that page. If they were to navigate the browser directly to that link and had permission to view the page it will be displayed. Menus do not affect access controll.

#### Force frontend / backend.

If you are trying to link from one section of a site to another these options may be helpful. For example you could use `Force frontend` in a backend link to remove the `admin` param from the URL so that clicking the link would direct users to the frontend of the site.

#### Params

Params allow you to build links to controller actions that may require slugs being set or additional params in the URL. For example you could create a link to `/users/users/edit` but that would not be any use without passing a user id. You could however create the link and specify params as follows:

	{"0":"123"}

This would make a url such as `/users/users/edit/123`. Another example with a slug:

	{"slug":"my-slug"}

Which would create a url (from a route such as `/foo/bar/:slug`) as `/foo/bar/my-slug`

> There are plans to make these types of parameters more user friendly in future releases. Currently `params` makes use of `JSON`.

#### Class

This is an optional config that will (when using the Infinitas menu builder) add the specified class to the link.

### Menu Module

The Menus plugin provides a module that can be loaded by the Modules plugin for loading menus dynamically. This module can be copied multiple times with each version specifying a different menu to load. Each module can also be assigned to a specific `module position` which will allow the menu to be moved around the sites layout.

This requires using Themes that have been built to make use of the Modules functionality.

> If you requre very customised markup for your Menus, you can create your own menu module and helper where the output can be custmoised. This will require a bit of PHP and HTML knowledge.