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

	$config['Comments'] = array(
		/**
		 * configuration
		 */
		'auto_moderate' => true,   // automatically accept and acticvate comments that are not spam
		'purge' => '4 weeks',      // automatic spam purge
		'time_limit' => '4 weeks', // after this time commets are closed
		'fields' => 'username,email,website,comment', // fileds for the form
		 
		/**
		 * rating params
		 */
		'spam_threshold' => -25, // below this things are just ignored
		'maximum_links' => 2,    // number of links before things are considered spammy
		'minimum_length' => 20   // below this its spammy
	 );