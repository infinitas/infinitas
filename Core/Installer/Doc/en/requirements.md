Infinitas was built using the CakePHP framework. It usually requires the latest version of CakePHP which is currently `2.3`. Besides this requirement you will also need the following:

### Server

Any modern server should be more than adequate to run an Infinitas powered application. It is highly recomended to go for a cloud based service such as `RackSpace` or `AWS` as this allows you full root access and the ability to install everything that you may require.

Most `VPS` solutions should also be adequate as they provide a similar server to what you would get with cloud services.

Infinitas does not make use of any PHP modules that are not installed with a default installation so you should not have any issues with shared hosting. With the low cost of cloud bases services there is little excuse not to though.

### PHP

Infinitas makes use of closures and other relativly new features from [PHP](http://php.net). Currently Infinitas is tested and known to run fine on PHP `5.3.x`. It may work fine on PHP `5.4.x` although there are currently issues with `E\_STRICT`.

In the near future these `E\_STRICT` issues will be resolved and hope to have Infinitas running well on all versions of [PHP](http://php.net) above `5.3.x`.

To make use of the shell commands available for automating a lot of tasks you will need `php-cli`. While this is not a requirement for general sites, for more advanced sites and development this is highly recommended.

### MySQL

Infinitas uses [MySQL](http://mysql.com) as a data store. Although CakePHP is able to make use of other database engines such as [PostgreSQL](http://www.postgresql.org/), [Microsoft SQL Server](http://www.microsoft.com/sqlserver/en/us/default.aspx) and [SQLite](http://www.sqlite.org/) at this time MySQL is the only supported engine. This is due to a number of `virtualFields` that run various opperations such as `COUNT()`, `MAX()` and `AVG()` that may not be compatible with other engines.

### Webserver

A webserver is requires to serve your website to the world. There are a large number of webservers available and for the most part should all work fine. One of the main requirements would be a [URL rewrite mechanism](http://en.wikipedia.org/wiki/Rewrite_engine) that can manage `friendly urls`. 

> CakePHP is designed to run without having `url rewriting` available. It may be possible to run Infinitas without it too, although it has not been tested nor is there any interest in making this work.

##### Cherokee webserver

[Cherokee](http://www.cherokee-project.com/) is a lightweight webserver written in `C` with no dependencies besides a standard `libc`. It has an easy to use admin interface for configuring `vHosts`, runs on virtually all systems and is compatible with a wide range of technologies such as `FastCGI`, `SCGI`, `PHP`, `uWSGI` and more.

> The Cherokee project has a smallish, friendly community on freenode \#cherokee that is able to help with configuration and other issues.

##### Nginx

[Nginx](http://nginx.org/) has been tested and is known to work fine.

##### Apache

[Apache](http://www.apache.org/) is one of the most wide spred webserver around. That does not make it the best though, with clunky configs, `.htaccess` files that are parsed on every request and a relativly large memory footprint you will almost always be better off using something else.

### Install them all

If you are looking for a quick start to get going the following should give you just about everything you need to be up and running. Once you have your server up and running log in and run the following from the command prompt (assuming you are running Ubuntu):

Make sure all your software is upto date.

	sudo apt-get update && sudo apt-get upgrade

For Cherokee we need to get the latest version via a `PPA`. To add a `PPA` this small python app is needed:

	sudo apt-get install python-software-properties

You can now add the [Cherokee PPA](https://launchpad.net/~cherokee-webserver/+archive/ppa)

	sudo apt-add-repository ppa:cherokee-webserver/ppa

Now that the dependencies are sorted out you can go ahead and install everything needed

- cherokee: the Cherokee server
- php5-dev: the dev version includes the latest code, for something more "stable" you can use `php5` instead
- php5-mysql: the drivers for MySQL on PHP
- mysql-server: the MySQL data store

	sudo apt-get install php5-dev cherokee php5-mysql mysql-server

Some additional things that could make your development life easier or just generally better

- php5-cli: the command line version of PHP
- php5-xdebug: a great tool for debuggin PHP
- php-apc: a PHP opcode cache and general memory store (much faster than the default file cache)

	sudo apt-get install php5-cli php5-xdebug php5-apc

You may also want to install the following tools that can be a great help for server admin work and security

- htop: a much easier to use `top`
- innotop: a `top` for mysql 
- iotop: a `top` for file I/O
- fail2ban: an app that will keep your server relativly save from automated hacking
- git: for cloning git repositories

	sudo apt-get install htop innotop iotop fail2ban git

If you are up and running you can head over to the docs for [installing Infinitas](/infinitas\_docs/Installer/installing-infinitas).