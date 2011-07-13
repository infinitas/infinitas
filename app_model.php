<?php
	App::import('Lib', 'Libs.LazyModel');
	
	/**
	 * @page AppModel AppModel
	 *
	 * @section app_model-overview What is it
	 *
	 * AppModel is the main model class that all other models will eventually
	 * extend. AppModel provides some methods through inheritance and also sets up
	 * a few configurations that are used throughout the application.
	 *
	 * A lot of the code that is found here is to help make development simpler
	 * but can easily be overloaded should you requre something a little bit
	 * different. Take the cache clearing for example, the default is that after
	 * a change in the database is detected any related cache will be deleted. Should
	 * you want something else to happen just overload the method in your model
	 * or the MyPluginAppModel.
	 *
	 * @section app_model-usage How to use it
	 *
	 * Usage is simple, extend your MyPluginAppModel from this class and then the
	 * models in your plugin just extend MyPluginAppModel. Example below:
	 *
	 * @code
	 *	// in APP/plugins/my_plugin/my_plugin_app_model.php create
	 *	class MyPluginAppModel extends AppModel{
	 *		// do not set the name in this model, there be gremlins
	 *	}
	 *
	 *	// then in APP/plugins/my_plugin/models/something.php
	 *	class Something extends MyPluginAppModel{
	 *		public $name = 'Something';
	 *		//...
	 *	}
	 * @endcode
	 *
	 * After that you will be able to directly access the public methods that
	 * are available from this class as if they were in your model.
	 *
	 * @code
	 *	$this->someMethod(); 
	 * @endcode
	 *
	 * @section app_model-see-also Also see
	 * @ref LazyModel
	 * @ref InfinitasBehavior
	 * @ref Event
	 * @ref InfinitasBehavior
	 */

	/**
	 * @brief main model class to extend
	 * 
	 * AppModel is the base Model that all models should extend, unless you are
	 * doing something completely different.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 * 
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	class AppModel extends LazyModel {
		/**
		 * The database configuration to use for the site.
		 *
		 * @var string
		 * @access public
		 */
		public $useDbConfig = 'default';

		/**
		 * @brief The prefix for the table this model is using
		 *
		 * This Should be the same throughout a plugin, and should be the same
		 * as the plugins name with a trailing _ some my_plugin should have a
		 * prefix of 'my_plugin_'
		 *
		 * @todo make this auto set in the constructor
		 *
		 * @var string
		 * @access public
		 */
		public $tablePrefix;

		/**
		 * Behaviors to attach to the site.
		 *
		 * @var string
		 * @access public
		 */
		public $actsAs = array(			
			'Libs.Infinitas',
			'Events.Event'
		);
		/**
		 * recursive level should always be -1
		 *
		 * @var string
		 * @access public
		 */
		public $recursive = -1;

		/**
		 * error messages in the model
		 *
		 * @todo this should either be named $errors or made protected
		 *
		 * @var string
		 * @access public
		 */
		public $errors = array();

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
		 * @access public
		 */
		public $autoCacheClear = true;

		/**
		 * @brief Constructor for models
		 *
		 * Throughout Infinitas this method is mainly used to define the validation
		 * rules for models. See below if there is any thing else specific to the
		 * model calling this method.
		 *
		 * @link http://api13.cakephp.org/class/model#method-Model__construct
		 *
		 * @throw E_USER_WARNING if the model is using AppModel for a virtual model.
		 * 
		 * @param mixed $id Set this ID for this model on startup, can also be an array of options, see Model::__construct().
		 * @param string $table Name of database table to use.
		 * @param string $ds DataSource connection name.
		 * @access public
		 *
		 * @return void
		 */
		public function __construct($id = false, $table = null, $ds = null) {
			$this->__getPlugin();

			if($this->tablePrefix != ''){
				$config = $this->getDataSource()->config;

				if(isset($config['prefix'])) {
					$this->tablePrefix = $config['prefix'] . $this->tablePrefix;
				}
			}

			parent::__construct($id, $table, $ds);

			$this->__setupDatabaseConnections();
			
			$thisClass = get_class($this);

			$ignore = array(
				'SchemaMigration',
				'Session'
			);
			if(php_sapi_name() != 'cli' && !in_array($this->alias, $ignore) && ($thisClass == 'AppModel' || $thisClass == 'Model')){
				trigger_error(sprintf(__('%s is using AppModel, please create a model file', true), $this->alias), E_USER_WARNING);
			}

			if (isset($this->_schema) && is_array($this->_schema) && php_sapi_name() != 'cli') {
				if($this->Behaviors->enabled('Event')) {
					$this->triggerEvent('attachBehaviors');
					$this->Behaviors->attach('Containable');
				}
			}
			elseif(php_sapi_name() == 'cli') {
				$this->actsAs = array();
			}
		}

		/**
		 * @brief Called before each save operation, after validation. Return a non-true result
		 * to halt the save.
		 *
		 * @link http://api13.cakephp.org/class/model#method-ModelbeforeSave
		 *
		 * @param $created True if this save created a new record
		 * @access public
		 *
		 * @return boolean True if the operation should continue, false if it should abort
		 */
		public function beforeSave($options = array()) {
			return parent::beforeSave($options);
		}

		/**
		 * @brief called after something is saved
		 *
		 * @link http://api13.cakephp.org/class/model#method-ModelafterSave
		 *
		 * @param $created True if this save created a new record
		 * @access public
		 *
		 * @return void
		 */
		public function afterSave($created){
			$this->__clearCache();
		}

		/**
		 * @brief called after something is deleted.
		 *
		 * @link http://api13.cakephp.org/class/model#method-ModelafterDelete
		 * 
		 * @access public
		 *
		 * @return void
		 */
		public function afterDelete(){
			$this->__clearCache();
		}

		/**
		 * @brief Delete all cahce for the plugin.
		 *
		 * Will automaticaly delete all the cache for a model that it can detect.
		 * you can overlaod after save/delete to stop this happening if you dont
		 * want all your cache rebuilt after a save/delete.
		 *
		 * @todo should use the clear_cache plugin for this
		 *
		 * @access private
		 *
		 * @return void
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
		}

		/**
		 * @brief get a unique list of any model field, used in the search
		 * 
		 * @param string $displayField the field to search by
		 * @param bool $primaryKey if true will return array(id, field) else array(field, field)
		 * @access public
		 *
		 * @return array the data from the find
		 */
		public function uniqueList($displayField = '', $primaryKey = false){
			if(empty($displayField) || !is_string($displayField) || !$this->hasField($displayField)){
				return false;
			}

			$primaryKey = ($primaryKey) ? $this->primaryKey : $displayField;

			return $this->find(
				'list',
				array(
					'fields' => array(
						$this->alias . '.' . $primaryKey,
						$this->alias . '.' . $displayField
					),
					'group' => array(
						$this->alias . '.' . $displayField
					),
					'order' => array(
						$this->alias . '.' . $displayField => 'asc'
					)
				)
			);
		}

		/**
		 * @brief Get the name of the plugin
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

			return ($this->plugin == null) ? $this->name : $this->plugin . '.' . $this->name;
		}

		/**
		 * @brief Get the current plugin.
		 *
		 * try and get the name of the current plugin from the parent model class
		 *
		 * @access private
		 *
		 * @return void
		 */
		private function __getPlugin() {
			$parentName = get_parent_class($this);

			if($parentName !== 'AppModel' && $parentName !== 'Model' && strpos($parentName, 'AppModel') !== false) {
				$this->plugin = str_replace('AppModel', '', $parentName);
			}
		}

		/**
		 * @brief add connection to the connection manager
		 * 
		 * allow plugins to use their own db configs. If there is a conflict,
		 * eg: a plugin tries to set a config that alreay exists an error will
		 * be thrown and the connection will not be created.
		 *
		 * default is a reserved connection that can only be set in database.php
		 * and not via the events.
		 *
		 * @code
		 *  // for databases
		 *	array(
		 *		'my_connection' => array(
		 *			'driver' => 'mysqli',
		 *			'persistent' => true,
		 *			'host' => 'localhost',
		 *			'login' => 'username',
		 *			'password' => 'pw',
		 *			'database' => 'db_name',
		 *			'encoding' => 'utf8'
		 *		)
		 *	)
		 *
		 *	// or other datasources
		 *	array(
		 *		'my_connection' => array(
		 *			'datasource' => 'Emails.Imap'
		 *		)
		 *	)
		 * @endcode
		 *
		 * @access private
		 *
		 * @return void
		 */
		private function __setupDatabaseConnections(){
			$connections = array_filter(current(EventCore::trigger($this, 'requireDatabaseConfigs')));

			foreach($connections as $plugin => $connection){				
				$key = current(array_keys($connection));
				$connection = current($connection);

				$alreadyUsed = strtolower($key) == 'default' || 
					(isset(ConnectionManager::getInstance()->config->{$key}) && ConnectionManager::getInstance()->config->{$key} !== $connection);
					
				if($alreadyUsed){
					trigger_error(sprintf(__('The connection "%s" in the plugin "%s" has already been used. Skipping', true), $key, $plugin), E_USER_WARNING);
					continue;
				}

				ConnectionManager::create($key, $connection);
			}
		}

		/**
		 * @brief wrapper for transactions
		 *
		 * Allow you to easily call transactions manually if you need to do saving
		 * of lots of data, or just nested relations etc.
		 *
		 * @code
		 *	// start a transaction
		 *	$this->transaction();
		 *
		 *	// rollback if things are wrong (undo)
		 *	$this->transaction(false);
		 *
		 *	// commit the sql if all is good
		 *	$this->transaction(true);
		 * @endcode
		 * 
		 * @access public
		 *
		 * @param mixed $action what the command should do
		 *
		 * @return see the methods for tranasactions in cakephp dbo
		 */
		public function transaction($action = null){
			$this->__dataSource = $this->getDataSource();
			
			$return = false;

			if($action === null){
				$return = $this->__dataSource->begin($this);
			}

			else if($action === true){
				$return = $this->__dataSource->commit($this);
			}

			else if($action === false){
				$return = $this->__dataSource->rollback($this);
			}

			return $return;
		}
	}

	/**
	 * @brief DRY model class to get the prefix in core models.
	 * 
	 * CoreAppModel is used by most of Infinitas core models. All this does is
	 * Set the table prefix so it does not need to be set in every {Core}AppModel
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * @internal
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	class CoreAppModel extends AppModel{
		/**
		 * the table prefix for core models
		 * 
		 * @var string
		 * @access public
		 */
		public $tablePrefix = 'core_';
	}
	
	EventCore::trigger(new stdClass(), 'loadAppModel');