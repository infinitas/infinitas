<?php 
/* SVN FILE: $Id$ */
/* ShortUrls schema generated on: 2010-10-19 10:10:47 : 1287481307*/
class ShortUrlsSchema extends CakeSchema {
	var $name = 'ShortUrls';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $short_urls = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'url' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'views' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 9),
		'dead' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>