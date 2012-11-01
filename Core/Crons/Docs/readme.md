The Crons plugin provides a single point of entry for running cron jobs on any Infinitas powered site. This makes it easy to manage and controll what crons are run and when they are run.

#### Basic idea

Instead of having to set up multiple different crons manually using the clunky `crontab -e`, with the Crons plugin you are able to configure a single cron that runs every minute and let the plugin handle the rest.

The Crons plugin will get a list of all the currently installed and active plugins and trigger the `runCrons` event to each of them. While doing this the Crons plugin will also keep tabs on memory usage and system load to make sure nothing is going amiss. If anything out the ordinary is going on the crons are able to defer actions untill the next run, or when memory and load average are back to normal levels.

It will also stop a second instance of a job running while the first is still busy. So if you were using the Crons to send out your monthly Newsletter it would not run a second time while the initial newsletters were still running.

#### Usage examples

The Crons plugin is used throughout the core of Infinitas to automate a number of tasks. Some of these include

- Clearing old Locks on records
- Sending out Newsletter subscriptions
- Removing `localhost` views from the ViewCounter data

There is also the Jobs plugin that can be used for more real time job queues.

#### Memory usage

The Crons plugin stores all the data in the database so that you can view reports on what is happening on your server while you are not around. This information is used to decide when things are too busy to run jobs.

This information is available in the [admin backend](/admin/crons).

[![](http://assets.infinitas-cms.org/docs/Core/Crons/status.png "Server information")](http://assets.infinitas-cms.org/docs/Core/Crons/status.png)

[![](http://assets.infinitas-cms.org/docs/Core/Crons/load.png "Load data")](http://assets.infinitas-cms.org/docs/Core/Crons/load.png)

> The Charts plugin provides helpers for graphing all kinds of data. The Charts in the Crons plugin are built using the Google charts engine through the Charts plugin.

#### Configuring crons

The Crons plugin can be set up with a single cron that runs the cron shell. Easy access to the required command can be found using the shell. The command is usually something along the following lines

	*/1 * * * * /full/path/to/your/app/Console/cake Crons.cron

To view the system generated command that you can copy and paste, from your apps root directory run

	./Console/cake Crons.cron help

If you have setup the CakePHP path in your enviroment you can also use the following

	cake Crons.cron help
