<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	$config['Newsletter'] = array(
		'from_email' => '',
		'from_name' => '',
		'greeting' => 'ehlo',
		'send_as' => 'both',
		'send_count' => 200,
		'send_interval' => 600,
		'send_method' => 'php',
		'smtp_username' => '',
		'smtp_password' => '',
		'smtp_host' => '',
		'smtp_out_going_port' => '',
		'smtp_timeout' => 100,
		'template' => 'default',
		'track_views' => true
	);