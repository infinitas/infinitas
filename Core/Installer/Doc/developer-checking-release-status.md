If you would like to check what has been changed you can run the `status` command on the shell. First type `cake installer.release` and at the prompt select `s` for [S]tatus.

The Installer will run through all the plugins comparing the `schema.php` file with the actual database. Any differences will be displayed in a list similar to below:

	|                 Plugin Schema status Console                |
	... Plugins that need to be updated ...

	|                        Local Changes                        |
	... Plugins that need new releases ...

	|                            All Ok                           |
	... Plugins that are upto date ...

Any plugins that have schema changes and require an update will be listed under `local changes`. The listing will look something like the following, showing the plugin that has changed along with the table and fields. It will also indicate what sort of change has been done such as `add`, `remove` or `change`


	Plugin          Table               Fields
	Contact         contact\_branches    [add: model, foreign\_key, indexes], [change: ordering]
	ServerStatus    core\_crons          [add: id, process\_id, year, month, day, start\_time], [remove: start\_mem]
	ShortUrls      	core\_short\_urls     [change: id]

See the docs for more information on [creating and updating releases](/infinitas\_docs/Installer/developer-creating-releases).