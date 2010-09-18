<?php 
/* SVN FILE: $Id$ */
/* ShortUrls schema generated on: 2010-09-18 18:09:22 : 1284828622*/
class ShortUrlsSchema extends CakeSchema {
	var $name = 'ShortUrls';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $short_urls = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'url' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>