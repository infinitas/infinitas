### Preface

The installation of most webservers will create a folder in `/var/www` for storing your web sites. This guide will assume that is the case and refer to this path. If you are using your own path there should not be any issues, you will just need to adjust the commands to match your setup as the guide progresses.

If you are going to use `git` for obtaining the code you do not need to worry about CakePHP as it is included as a submodule. Make sure to include the submodules if you want to have CakePHP included automatically (recommended).

CakePHP and all other plugins

	git submodule update --init

Or just CakePHP with no other plugins

	git submodule CakePHP update --init

If you do not want to use the included CakePHP repo see the [installation guide](/infinitas\_docs/Installer/cakephp-installation) for CakePHP.

The code for Infinitas is open sourced under the MIT licence. You can obtain free copies of the code from the following locations:

- git repo: `git://github.com/infinitas/infinitas.git`
- [zip download](https://github.com/infinitas/infinitas/zipball/master)
- [tar.gz download](https://github.com/infinitas/infinitas/tarball/master)

Most times you will run more than one website on a server. We will create a `sites` directory inside `/var/www/` that will hold all our public facing websites. You can do this by first navigating to the existing `/var/www/` folder:

	cd /var/www

And then creating the `sites` directory

	mkdir sites

To keep things manageble each site will be created with the following structure. `public\_html` will be the publicly accesible folder where the code will be kept.

	/var/www/sites/example.com/
	/var/www/sites/example.com/public\_html

This allows us to create any logs related to `example.com` within that directory while keeping the publicly accesible code out of reach.

### Obtain via git

Navigate to the sites directory

	cd /var/www/sites

You can now clone Infinitas into a directory that we will late make accesible in the webserver configuration. :

	git clone git://github.com/infinitas/infinitas.git example.com/public\_html

Change to the newly created folders

	cd example.com/public\_html

If you would like to add any plugins now is a good time. You can add single plugins easily using `git` assuming there was a plugin called `SomePlugin` you would use the following command:

	git submodule init Plugin/SomePlugin

Repeat that for each additional plugin you would like to install. If you would rather have all the plugins you can use the following. (You can disable Plugins later on through the backend)

	git submodule init

After you have initialised the plugins you require you need to updte the repository so that the code is fetched and downloaded to your install.

	git submodule update

To get everything in one command (recomended) use the following single command (includes the required CakePHP code also)

	git submodule update --init --recursive


### Obtain via download

meh

### System check

You should now have a copy of Infinitas in `/var/www/sites/example.com/public\_html` that looks like the following. Make sure the permissions are correct for your webserver to access and serve the files.

	$ ls -al /var/www/sites/example.com/public\_html
	drwxrwxr-x 17 www-data   www-data   4096 2012-10-25 21:09 .
	drwxrwxr-x  3 www-data   www-data   4096 2012-10-04 17:13 ..
	drwxrwxr-x  4 www-data   www-data   4096 2012-10-04 17:29 Config
	drwxrwxr-x  4 www-data   www-data   4096 2012-10-04 17:18 Console
	drwxrwxr-x  2 www-data   www-data   4096 2012-10-04 17:18 Controller
	drwxrwxr-x 34 www-data   www-data   4096 2012-10-28 12:23 Core
	drwxrwxr-x  7 www-data   www-data   4096 2012-10-26 15:50 Developer
	drwxrwxr-x  8 www-data   www-data   4096 2012-10-29 00:01 .git
	-rw-rw-r--  1 www-data   www-data    700 2012-10-04 17:18 .gitignore
	-rw-rw-r--  1 www-data   www-data   1798 2012-10-28 00:21 .gitmodules
	-rw-rw-r--  1 www-data   www-data   2969 2012-10-04 17:18 .htaccess
	-rw-rw-r--  1 www-data   www-data    642 2012-10-04 17:18 index.php
	drwxrwxr-x  3 www-data   www-data   4096 2012-10-04 17:18 Lib
	drwxrwxr-x  3 www-data   www-data   4096 2012-10-04 17:18 Locale
	drwxrwxr-x  2 www-data   www-data   4096 2012-10-05 02:40 Model
	drwxrwxr-x 15 www-data   www-data   4096 2012-10-20 23:59 Plugin
	-rw-rw-r--  1 www-data   www-data   2324 2012-10-04 17:18 README
	drwxrwxr-x  3 www-data   www-data   4096 2012-10-04 17:18 Test
	drwxrwxr-x  6 www-data   www-data   4096 2012-10-25 21:41 tmp
	-rw-rw-r--  1 www-data   www-data   2388 2012-10-04 17:18 .travis.yml
	-rw-rw-r--  1 www-data   www-data   8935 2012-10-04 17:18 versions.txt
	drwxrwxr-x  7 www-data   www-data   4096 2012-10-04 17:18 View
	drwxrwxr-x  8 www-data   www-data   4096 2012-10-27 00:20 webroot
