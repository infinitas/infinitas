To get Infinitas up and running on your server you will need to have an upto date copy of the CakePHP framework. There is two main methods of obtaining the code required to setup your own copy of Infinitas. The first method is using [git](http://git-scm.com/) and is the prefered method. Alternativly you may just download the code as a zip or tarball.

Git is recomended as it makes obtaining updates to the code base and aditional plugins much simpler.

> Both [Infinitas](http://github.com/infinitas/infinitas) and [CakePHP](http://github.com/cakephp/cakephp) are available on [GitHub](http://github.com).

Please check the [requirements](/infinitas\_docs/Installer/requirements) that are needed to run Infinitas on your server. If you do not already have CakePHP installed, have a look at the [CakePHP installation guide](/infinitas\_docs/Installer/cakephp-installation)

You will need a copy of Infinitas available on your server, you can find information [here](/infinitas\_docs/Installer/obtaining-infinitas) on how to get the code.

> Currently the installer does not create databases. You will need to manually create the database where the Infinitas data will be stored.

You should make sure that all the permissions are correct on the installation before proceeding. 

### Install Shell

Kinda flakey at the moment. Run `cake installer.install` which will give you the following options:

	[E]verything
	[P]lugin
	[M]odule
	[T]heme
	Plugin [A]dd-on
	[L]icence
	[H]elp
	[Q]uit

Select `E` for everything and follow the prompts. You will be given an oppertunity to select your database engine and enter the login details.

### Web Install

If you do not want to make use of the shell installer you can use the web based installer. This provides a `GUI` that will guide you through the installation process.

#### Welcome

This page will show any issues that may need to be resolved before the installation can continue.

[![](http://assets.infinitas-cms.org/docs/Core/Installer/welcome.png "Welcome screen")](http://assets.infinitas-cms.org/docs/Core/Installer/welcome.png)

#### Database config

Here you will be given the chance to choose an data store and enter the login details that Infinitas will use. 

[![](http://assets.infinitas-cms.org/docs/Core/Installer/database.png "Database configuration")](http://assets.infinitas-cms.org/docs/Core/Installer/database.png)

You are also able to enter `root` credentials should the user provided for the Infinitas installation not have the required privileges of creating and modifying tables. These details are not stored, only used for the installation and then discarded.

[![](http://assets.infinitas-cms.org/docs/Core/Installer/advanced.png "Database root credentials")](http://assets.infinitas-cms.org/docs/Core/Installer/advanced.png)

#### Plugin options

Once the data store has been configured and tested to be working you will be able to pick from the available plugins that should be installed. If there is anything you do not want installed you can uncheck the box next to the plugin before proceeding.

You can also chose to install sample data that may be helpfull to new users. The sample data will show how Infinitas can be used to achieve certain things, seasoned users may find this data gets in the way and will want to skip this option.

> Core plugins such as the Users plugin and Filemanager can not be removed as these are vital to the functionality of Infinitas.

Depending on the plugins that have been chosen the installer might take a little while to complete. This could be anything from a couple of seconds to a minute or two.

[![](http://assets.infinitas-cms.org/docs/Core/Installer/plugins.png "Install plugins and sample data")](http://assets.infinitas-cms.org/docs/Core/Installer/plugins.png)

#### Admin details

Once all the plugins have been installed with the required database tables and fields you will be given a form to create the initial `admin` user. Once you have created the initial `admin` user you will be able to log into the application and continue to create and manage the system. 

[![](http://assets.infinitas-cms.org/docs/Core/Installer/user.png "Admin user")](http://assets.infinitas-cms.org/docs/Core/Installer/user.png)

> Without this `admin` user nobody will be able to gain access to the backend. **Skipping this step is not recomended.**

### Done

You should now have a working copy of Infinitas running. You can continue with the rest of the docs or start playing by clicking the provided links at the end of the installation. 

[![](http://assets.infinitas-cms.org/docs/Core/Installer/done.png "Install complete")](http://assets.infinitas-cms.org/docs/Core/Installer/done.png)