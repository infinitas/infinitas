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

		//var $tablePrefix = 'core_';

		/**
		* Behaviors to attach to the site.
		*/
		var $actsAs = array(
			'Containable',
			'Libs.Infinitas',
			'Events.Event',
			//'Libs.Logable',
			'DebugKit.Timed',

			//'Libs.AutomaticAssociation'
		);

		var $recursive = -1;

		/**
		* error messages in the model
		*/
		var $_errors = array();

		/**
		 * @var string Plugin that the model belongs to.
		 */
		var $plugin = null;

		function __construct($id = false, $table = null, $ds = null) {
			$this->__getPlugin();
			parent::__construct($id, $table, $ds);

			if (isset($this->_schema) && is_array($this->_schema)) {
				if($this->Behaviors->enabled('Event')) {
					$this->triggerEvent('attachBehaviors');
				}
			
				if (array_key_exists('lft', $this->_schema) && array_key_exists('rght', $this->_schema)) {
					$this->Behaviors->attach('Tree');
				}
			}
		}

		/**
		 * Get model name.
		 *
		 * Get a model name with the plugin prepended in the format used in
		 * CR::init() and Usefull for polymorphic relations.
		 *
		 * @return string Name of the model in the form of Plugin.Name.
		 */
		function modelName() {
			if($this->plugin == null) {
				$this->__getPlugin();
			}

			return $this->plugin == null ? $this->name : $this->plugin . '.' . $this->name;
		}

		/**
		 * Get the current plugin.
		 *
		 * try and get the name of the current plugin from the parent model class
		 *
		 * @return the plugin name
		 */
		private function __getPlugin() {
			$parentName = get_parent_class($this);

			if($parentName !== 'AppModel' && $parentName !== 'Model' && strpos($parentName, 'AppModel') !== false) {
				$this->plugin = str_replace('AppModel', '', $parentName);
			}
		}
	}