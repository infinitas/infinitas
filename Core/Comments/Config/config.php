<?php
/**
 * Configuration defaults for the comments plugin
 *
 * Default options should always be overlaoded in the backend via the
 * congfigs plugin.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Comments
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

$config['Comments'] = array(
	'auto_moderate' => true,   // automatically accept and acticvate comments that are not spam
	'purge' => '4 weeks',      // automatic spam purge
	'time_limit' => '4 weeks', // after this time commets are closed
	'fields' => 'username,email,website,comment', // fileds for the form

	'spam_threshold' => -25, // below this things are just ignored
	'maximum_links' => 2,    // number of links before things are considered spammy
	'minimum_length' => 20,   // below this its spammy
	'time_format' => 'Y'
);