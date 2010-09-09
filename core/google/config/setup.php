<?php
/**
* Google config class file.
*
* setup the google helper.
*
* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @filesource
* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
* @link http://infinitas-cms.org
* @package google
* @subpackage google.setup
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
*/
class GoogleConfig {
	/**
	* Current version: http://github.com/dogmatic/cakephp_google_plugin
	*
	* @access public
	* @var string
	*/
	var $version = '0.1';

	/**
	* Settings
	*
	* @access public
	* @var array
	*/
	var $settings = array();

	/**
	* Singleton Instance
	*
	* @access private
	* @var array
	* @static
	*/
	var $__instance;

	/**
	*
	* @access private
	* @return void
	*/
	function __construct() {
		/**
		* analytics setup
		*/
		$analytics = array(
			'profile_id' => 'xxxxxxxx-x'
			);
		Configure::write('Google.Analytics', $analytics);
	}
}