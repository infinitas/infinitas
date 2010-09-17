<?php
	App::import('Lib', 'Libs.LazyModel');

	/**
	 * AppModel is the base Model that all models should extend, unless you are
	 * doing something completely different.
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

		/**
		* Behaviors to attach to the site.
		*/
		public $actsAs = array(			
			'Libs.Infinitas',
			'Events.Event'			
			//'Libs.Logable',
			//'Libs.AutomaticAssociation'
		);

		public $recursive = -1;

		/**
		* error messages in the model
		 * @access public
		*/
		public $_errors = array();

		/**
		 * Plugin that the model belongs to.
		 * 
		 * @var string
		 * @access public
		 */
		public $plugin = null;

		/**
		 * auto delete cache
		 *
		 * @var bool
		 */
		public $autoCacheClear = true;

		public function __construct($id = false, $table = null, $ds = null) {
			$this->__getPlugin();
			parent::__construct($id, $table, $ds);			

			if (isset($this->_schema) && is_array($this->_schema)) {
				if($this->Behaviors->enabled('Event')) {
					$this->triggerEvent('attachBehaviors');
					$this->Behaviors->attach('Containable');
				}
			}
		}

		/**
		 * called after something is saved
		 */
		public function afterSave($created){
			$this->__clearCache();
		}

		/**
		 * called after something is deleted.
		 */
		public function afterDelete(){
			$this->__clearCache();
		}

		/**
		 * Delete all cahce for the plugin.
		 *
		 * Will automaticaly delete all the cache for a model that it can detect.
		 * you can overlaod after save/delete to stop this happening if you dont
		 * want all your cache rebuilt after a save/delete.
		 */
		private function __clearCache(){
			if(in_array($this->plugin, Cache::configured())){
				$_cache = Cache::getInstance()->__config[$this->plugin];
				$path = CACHE.str_replace('.', DS, $_cache['prefix']);
				if(CACHE !== $path && is_dir($path)){
					$Folder = new Folder($path);
					if($Folder->delete()){
						$this->log('deleted: '.$path, 'cache_clearing');
					}
					else{
						$this->log('failed: '.$path, 'cache_clearing');
					}
				}
				else{
					$this->log('skip: '.$path, 'cache_clearing');
				}
			}

			return true;
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