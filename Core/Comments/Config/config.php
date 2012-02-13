<?php
	/**
	 * @brief Configuration defaults for the comments plugin
	 *
	 * Default options should always be overlaoded in the backend via the
	 * congfigs plugin.
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
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