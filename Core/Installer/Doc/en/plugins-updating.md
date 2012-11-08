Infinitas allows updating plugins though shells and in the [admin backend](/admin/plugins). The process is similar to [installing plugins](/infinitas\_docs/Installer/plugins-installing).


#### Shell

To bring up the Installer options run the command `cake installer.install`. You should be given options similar to the following:

	[E]verything
	[P]lugin
	[M]odule
	[T]heme
	Plugin [A]dd-on
	[L]icence
	[H]elp
	[Q]uit

	What do you wish to release?
	>

To update a plugin enter `p` and hit `return`. You will be given a new list of options similar to the following:

	[U]pdate a plugin
	[Z]ip file install
	[O]ver the air
	[L]ocal install
	[H]elp
	[Q]uit

Enter `u` for update and hit `return`. The Installer will figure out what plugins require updates and then output a list. To run all updates you can enter `a` or enter the number of the corresponding plugin and hit `return` to confirm.

If the plugin has been updated you will see the message `Contact Plugin updated`. Press any key to continue. Repeat the process of selecting plugins to update or `q` to quit the installer update.

If there is a problem running an update you will be shown an error. This could be for a number of reasons but the errors should provide a decent hint as to what the problem is.

	Update for Security has failed (Table "core_ip_addresses" already exists in database.)

#### Backend

The process for updating plugins through the backend has not been finalised just yet. In the mean time you can use the shell method for updating plugins.