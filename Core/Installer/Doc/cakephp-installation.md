You can obtain the CakePHP code from a number of locations including the following:

- git repo: `git://github.com/cakephp/cakephp.git`
- [zip download](https://github.com/cakephp/cakephp/zipball/master)
- [tar.gz download](https://github.com/cakephp/cakephp/tarball/master)

Git is by far the most prefered method as you are sure to have the latest upto date code. With the speed that opensource progresses other methods such as `apt-get`, PHP's `PEAR` and others are not recomended as they are almost always outdated. This will mean that you end up with a copy that may have bugs and security issues that have aleady been fixed in later versions.

### Preface

It is best to keep your copy of CakePHP in `shared location` so that you will be able to use the same copy for multiple applications. This guide will use `/var/www/frameworks/cakephp` as that location. 

To make this copy of CakePHP available without having to modify every single app we can make a small addition to the `php.ini` file that tells PHP where it can find Cake. Here we will assume you are using `PHP FPM` to serve request, although this could be something else like `cgi` or `apache`.

	sudo nano /etc/php5/fpm/php.ini

You will look for the include configuration, `ctrl + w` will give a search option in `nano`. Other editors my use `ctrl + f` or similar. The text to search for is `include_path`. Once found you will want to adjust the path so that is resembles the following:

	include\_path = "/usr/share/php:/var/www/frameworks/cakephp/lib"

If you have other include paths make sure to leave them in tact. The above line shows an include for two locations, the first is php shared libs from `PEAR` and the second is our `CakePHP` inlcude path.

### Install with Git

If you do not already have a copy of CakePHP you will need to clone a copy. In terminal change to the `/var/www` folder

	cd /var/www

You can then clone CakePHP with the above url into our shared location:

	git clone git://github.com/cakephp/cakephp.git frameworks/cakephp

Make sure you are on the correct `branch` by changing to the newly created dir 

	cd frameworks/cakephp

and then `checking` it out. The current version in use is `2.3`

	git checkout 2.3

### Install with download

**This option is not recomended as it makes obtaining updates more difficult.**

Change to the `/var/www` folder

	cd /var/www

You will need to create the folder for CakePHP to go into.

	mkdir frameworks && mkdir frameworks/cakephp

You can then change to the newly created folder

	cd frameworks/cakephp

To download a copy of the CakePHP framework you can use wget with one of the urls from above. For a `zip` file you can use the following

	wget -O cakephp.zip https://github.com/cakephp/cakephp/zipball/master

For a `tar.gz` file use the following

	wget -O cakephp.tar.gz https://github.com/cakephp/cakephp/tarball/master

Once you have a copy of the code you will need to extract it. You can do that with the following command depending on what you have downloaded

For the `zip` file use:

	unzip cakephp.zip

For the `tar.gz` file use:

	tar -zxvf cakephp.tar.gz

### System check

You should now have a copy of CakePHP that is ready to use for all your apps. Make sure that your webserver has permisson to use these files. Running the command `ls -al /var/www/frameworks/cakephp` you should see something like the following:

	drwxrwxr-x  8 www-data   www-data    4096 2012-09-17 17:38 .
	drwxrwxr-x  8 www-data   www-data    4096 2012-07-08 00:54 ..
	drwxrwxr-x 14 www-data   www-data    4096 2012-09-15 12:29 app
	-rw-rw-r--  1 www-data   www-data     177 2012-04-30 12:20 build.properties
	-rw-rw-r--  1 www-data   www-data    8647 2012-07-02 23:52 build.xml
	drwxrwxr-x  8 www-data   www-data    4096 2012-10-24 19:04 .git
	-rw-rw-r--  1 www-data   www-data     106 2012-09-15 12:29 .gitignore
	-rw-rw-r--  1 www-data   www-data     139 2012-09-15 12:29 .htaccess
	-rw-rw-r--  1 www-data   www-data    1405 2012-09-15 12:29 index.php
	drwxrwxr-x  3 www-data   www-data    4096 2012-06-18 21:46 lib
	drwxrwxr-x  2 www-data   www-data    4096 2011-11-14 19:52 plugins
	-rw-rw-r--  1 www-data   www-data    1665 2012-09-17 17:38 README.md
	-rw-rw-r--  1 www-data   www-data    3305 2012-09-15 12:29 .travis.yml
	drwxrwxr-x  2 www-data   www-data    4096 2011-11-14 19:52 vendors

If everything went well you can move onto [installing Infinitas](/infinitas\_docs/Installer/installing-infinitas).