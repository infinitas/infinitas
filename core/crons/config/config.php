<?php
	$config = array();
	$config['Cron'] = array(
		'run_every' => '5 minutes',			// how often to run the cron
		'email_fails' => true,				// report broken runs to admin
		'email_after' => 5,					// after this amount of fails an email will be sent to the admin user
		'assume_ended_after' => '1 hour',	// if cant check | kill the process mark as failed and continue
		'clear_logs' => '6 months'			// clear any data older than the strtotime specified
	);