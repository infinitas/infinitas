<?php
	App::import('Lib', 'Libs.LazyModel');

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
	class AppModel extends LazyModel {
		/**
		* The database configuration to use for the site.
		*/
		public $useDbConfig = 'default';

		//var $tablePrefix = 'core_';

		/**
		* Behaviors to attach to the site.
		*/
		public $actsAs = array(
			'Containable',
			'Libs.Infinitas',
			'Events.Event',
			//'Libs.Logable',
			'DebugKit.Timed',

			//'Libs.AutomaticAssociation'
		);

		public $recursive = -1;

		/**
		* error messages in the model
		*/
		public $_errors = array();

		/**
		 * @var string Plugin that the model belongs to.
		 */
		public $plugin = null;

		public function __construct($id = false, $table = null, $ds = null) {
			$this->__getPlugin();
			parent::__construct($id, $table, $ds);			

			if (isset($this->_schema) && is_array($this->_schema)) {
				if($this->Behaviors->enabled('Event')) {
					$this->triggerEvent('attachBehaviors');
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
		public function modelName() {
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

	/**
	 * CoreAppModel is used by most of Infinitas core models. All this does is
	 * Set the table prefix so it does not need to be set in every {Core}AppModel
	 */
	class CoreAppModel extends AppModel{
		public $tablePrefix = 'core_';
	}