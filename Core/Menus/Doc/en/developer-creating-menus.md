There are a number of ways to generate menus from the data in the database. The most simple method is to use the MenuHelper. For example you have a menu with type set to `example-menu` you would use the following code.

	$menus = ClassRegistry::init('Menus.MenuItem')->getMenu('example-menu');
	echo $this->Menu->nestedList($menus, $type);

MVC purists might be up in arms over this bit of code, but realistically there is not much choice. Given that a user could create hundreds of different menus and there is little way to know what menu is actually going to be used on any particular page some MVC rules need to be **bent**. It is either this, or load all of the menus from a component callback such as `beforeRender` and possibly load masses of data that is never used, or load the menu as needed while breaking a few MVC rules.

So long as the `Model` section of the code is limited to a very small bit (eg: one line), the impact is minimal. Dont go writing hude finds with `conditions`, `joins` and so on though.

Alternativly you may build the markup manually or with your own helper methods.