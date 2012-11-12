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
	 * @package Core.Webmaster.Config
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.9
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	$config['Webmaster'] = array(
		'last_modified' => '-2 weeks',   // default time ago that things were changed
		'change_frequency' => 'monthly', // never, yearly, monthly, daily, hourly, always
		'priority' => 0.5				 // 0 -> 1
	);