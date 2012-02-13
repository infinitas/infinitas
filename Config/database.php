<?php
	/**
	* Comment Template.
	*
	* @todo Implement .this needs to be sorted out.
	*
	* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	*
	* Licensed under The MIT License
	* Redistributions of files must retain the above copyright notice.
	* @filesource
	* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	* @link http://infinitas-cms.org
	* @package sort
	* @subpackage sort.comments
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	* @since 0.5a
	*/

	class DATABASE_CONFIG {
		var $default = array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host' => 'localhost',
			'port' => '',
			'login' => 'bitcore',
			'password' => 'bitcore',
			'database' => 'bitcore',
			'schema' => '',
			'prefix' => '',
			'encoding' => 'utf8'
		);

		var $test = array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host' => 'localhost',
			'port' => '',
			'login' => 'test',
			'password' => 'test',
			'database' => 'test',
			'schema' => '',
			'prefix' => '',
			'encoding' => 'utf8'
		);
	}
?>
