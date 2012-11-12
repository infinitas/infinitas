<?php
	/*
	 * Short Description / title.
	 *
	 * Overview of what the file does. About a paragraph or two
	 *

	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Core.Newsletter.Config
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
	 */

	$config['Newsletter'] = array(
		'from_email' => '',
		'from_name' => '',
		'send_as' => '',
		'send_count' => 200,
		'send_interval' => 600,
		'send_method' => 'smtp',
		'smtp_username' => '',
		'smtp_password' => '',
		'smtp_host' => '',
		'smtp_out_going_port' => 49,
		'smtp_timeout' => 100,
		'template' => 'default',
		'track_views' => true
	);