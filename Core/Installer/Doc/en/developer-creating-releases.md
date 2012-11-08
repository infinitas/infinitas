When building a plugin you may wish to use the built in database migrations and installation process. This makes it easy to share changes between developers while also making sharing your plugin much easier. Once an release has been created or updated users can easily install and keep their copy upto date.

> Without a release Infinitas will not be able to install or activate your plugin automatically. To avoid having to hardcode plugins into the `bootstrap.php` file it is recomended to create a release for your plugin.

There are two different times you will create a release. First when the plugin has been created and then any time you have updates to the database schema that requires sharing.

You can check the [release status](/infinitas\_docs/Installer/developer-checking-release-status) in the terminal if you are not sure what needs updating.

#### What is a release

A release creates a number of files related to the plugin. The most important parts are the plugins config file and the schema. This information is used by Infinitas when installing or updating a plugin.

- `Config/config.json` the plugins config details
- `Config/Schema/schema.php` the plugins latest schema definition
- `Config/releases/map.php` the release map
- `Config/releases/xxxxx\_PluginName.php` the details of the release (Migration file)

The `config.json` file contains basic information such as a [UUID](http://en.wikipedia.org/wiki/Universally\_unique\_identifier) for the plugin, globally unique for each plugin.

A schema file defines all the `tables` and `fields` that are used in the plugin. This is what Infinitas uses to build the database when installing plugins. This information is always the latest upto date version (besides any local changes that have not been released). If you install a plugin the table in the database will match the schema file.

The migrations contain information that describes what is being changed at each version and how Infinitas can undo the change. For example, if you added a field `foo` to the `users` table and created a release it will contain something like the following:

###### Up
- add: `users` => [`foo`]

###### Down
- remove: `users` => [`foo`]

The `map.php` file records the order in which migrations are run, and allows Infinitas to figure out how partial updates work such as from version `2` -> `4` instead of `1` -> `10` for example.

#### First time release

When you create a release for the first time Infinitas will require you to fill out the information about the plugin being released. This includes things like who the developer is, the licence and a description of the plugin. It will also ask about any dependencies and figure out what models are going to be included.

To run a release, in terminal type `cake installer.release` and select `p` for plugin. A list of all available plugins will be displayed with numbers next to them. Select the plugin by typing its number and press `enter`. Follow the prompts to complete the release.

> Infinitas is not designed to work with CakePHP AppModel instances even if you are using conventions. All models **must** have a model file created.

#### Updating a release

The process for updating a release is very similar to creating one for the first time. You will be given the option to update the plugin information that you entered with the initial release. If nothing has changed, the only required field is a new version number for the plugin.

You can update a release in the same manner as creating a new one, `cake installer.release`.

Once you have filled out all the information the Installer will read all the model data and build the required migrations. These will be written to disk along with the configuration for the plugin.
