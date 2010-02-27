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
	class AppModel extends Model {
		/**
		* The database configuration to use for the site.
		*/
		var $useDbConfig = 'default';
		var $tablePrefix = 'core_';

		/**
		* Behaviors to attach to the site.
		*/
		var $actsAs = array(
			'Containable',
			'Libs.Lockable',
			'Libs.Logable',
			'Events.Event'
			//'Libs.AutomaticAssociation'
		);

		var $blockedPlugins = array(
			'DebugKit',
			'Filter',
			'Libs'
		);

		/**
		 * JSON error messages.
		 *
		 * Set up some errors for json.
		 * @access public
		 */
		var $_json_messages = array(
		    JSON_ERROR_NONE      => 'No error',
		    JSON_ERROR_DEPTH     => 'The maximum stack depth has been exceeded',
		    JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
		    JSON_ERROR_SYNTAX    => 'Syntax error',
		);

		/**
		* error messages from checking json
		*/
		var $__jsonErrors = array();

		/**
		* error messages in the model
		*/
		var $_errors = array();

		/**
		* convert json data.
		*
		* takes a string and returns some data. can pass return false for validation.
		*
		* @params string $data a string of json data.
		* @params array $config the params to pass to json_decode (assoc && depth)
		* @params bool $return will return the array/object by default but can be set to false to just check its valid.
		*/
		function getJson($data = null, $config = array(), $return = true){
			if (!$data) {
				$this->_errors[] = 'No data for json';
				return false;
			}

			$defaultConfig = array('assoc' => true);
			$config = array_merge($defaultConfig, (array)$config);
			$json = json_decode($data, $config['assoc']);

			if (!$json) {
				$this->__jsonErrors[] = $this->_json_messages[json_last_error()];
				return false;
			}

			if ($return) {
				return $json;
			}

			unset($json);
			return true;
		}

		/**
		* Get a list of plugins.
		*
		* Just gets a list of plugins and returns them after rempving the plugins
		* that should not be displayed to the user.
		*
		* @return array an array of all the plugins in infinitas.
		*/
		function getPlugins($skipBlocked = true){
			$plugins = Configure::listObjects('plugin');

			if ($skipBlocked === false) {
				return $plugins;
			}

			foreach($plugins as $plugin){
				if (!in_array($plugin, $this->blockedPlugins)){
					$return[Inflector::underscore($plugin)] = $plugin;
				}
			}

			return array('' => 'None') + (array)$return;
		}
		
		function setTablePrefix(){
			//Hack to make Migrations work
			if($this->useTable == 'schema_migrations'){
				$this->tablePrefix = '';
			}
		}
	}
?>