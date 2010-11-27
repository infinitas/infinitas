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
		 * @access public
		 */
		public $autoCacheClear = true;

		/**
		 * @brief Constructor. Binds the model's database table to the object.
		 *
		 * @link http://api.cakephp.org/class/model#method-Model__construct
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
		 * @brief called after something is saved
		 *
		 * @link http://api.cakephp.org/class/model#method-ModelafterSave
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
		 * @link http://api.cakephp.org/class/model#method-ModelafterDelete
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

			$displayField = $displayField;

			$primaryKey = $primaryKey
				? $this->primaryKey
				: $displayField;

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

			return $this->plugin == null ? $this->name : $this->plugin . '.' . $this->name;
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

			$existingConnections = ConnectionManager::getInstance()->config;

			foreach($connections as $plugin => $connection){
				$key = current(array_keys($connection));
				$connection = current($connection);

				if(strtolower($key) == 'default' || (isset($existingConnections->{$key}) && $existingConnections->{$key} !== $connection)){
					trigger_error(sprintf(__('The connection "%s" in the plugin "%s" has already been used. Skipping', true), $key, $plugin), E_USER_WARNING);
					continue;
				}

				ConnectionManager::create($key, $connection);
			}
		}


		/**
		 * general validation rules
		 * can be moved to a validation behavior
		 */
		/**
		 * @brief This can either be empty or a valid json string.
		 *
		 * @todo move to a validation behavior
		 * @todo no point having empty as can use 'allowEmpty' in the rule
		 *
		 * @param array $field the field being validated
		 * @access public
		 *
		 * @return bool is it valid
		 */
		public function validateEmptyOrJson($field){
			return strlen(current($field)) == 0 || $this->validateJson(current($field));
		}

		/**
		 * @brief allow the selection of one field or another or nothing
		 *
		 * @todo move to a validation behavior
		 * @todo no point having empty as can use 'allowEmpty' in the rule
		 *
		 * @param array $field not used
		 * @params array $fields list of 2 fields that should be checked
		 * @access public
		 * 
		 * @return bool is it valid?
		 */
		public function validateNothingEitherOr($field, $fields = array()){
			return
				// nothing
				empty($this->data[$this->alias][$fields[0]]) && empty($this->data[$this->alias][$fields[1]]) ||

				// either or
				$this->validateEitherOr($field, $fields);
				
		}

		/**
		 * @brief allow the selection of one field or another
		 *
		 * This is used in times where one thing should be filled out and another
		 * should be left empty. 
		 *
		 * @todo move to a validation behavior
		 *
		 * @param array $field not used
		 * @params array $fields list of 2 fields that should be checked
		 * @access public
		 *
		 * @return bool is it valid?
		 */
		public function validateEitherOr($field, $fields){
			return
				// either
				empty($this->data[$this->alias][$fields[0]]) && !empty($this->data[$this->alias][$fields[1]]) ||

				// or
				!empty($this->data[$this->alias][$fields[0]]) && empty($this->data[$this->alias][$fields[1]]);
		}

		/**
		 * @brief check for urls either /something/here or full
		 * 
		 * this can be a url relative to the site /my/page or full like
		 * http://site.com/my/page it can also be empty for times when the selects
		 * are used to build the url
		 *
		 * @todo move to a validation behavior
		 * @todo remove current($field) == '' || as 'notEmpty' works fine
		 *
		 * @param array $field the field being validated
		 * @access public
		 *
		 * @return bool is it valid
		 */
		public function validateUrlOrAbsolute($field){
			return
				// not in use
				current($field) == '' ||

				// aboulute url
				substr(current($field), 0, 1) == '/' ||

				// valid url
				Validation::url(current($field), true);
		}

		/**
		 * @brief compare 2 fields and make sure they are the same
		 *
		 * This method can compare 2 fields, with password having a special meaning
		 * as they will be hashed automatically.
		 *
		 * the order of password fields is important as you could end up hashing
		 * the hashed password again and still having the other one as plain text
		 * which will always fail.
		 *
		 * basic usage
		 *
		 * @code
		 *	// random fields
		 *	'rule' => array(
		 *		'validateCompareFields', array('field1', 'field2')
		 *	),
		 *	'message' => 'fields do not match'
		 *
		 *	// real world
		 *	'rule' => array(
		 *		'validateCompareFields', array('email', 'compare_email')
		 *	),
		 *	'message' => 'The email addresses you entered do not match'
		 *
		 *	'rule' => array(
		 *		'validateCompareFields', array('compare_password', 'password')
		 *	),
		 *	'message' => 'The email addresses you entered do not match'
		 * @endcode
		 *
		 * @todo move to a validation behavior
		 *
		 * @param array $field not used
		 * @access public
		 * 
		 * @param bool $fields the fields to compare
		 */
		public function validateCompareFields($field, $fields){
			if($fields[0] == 'password'){
				return Security::hash($this->data[$this->alias][$fields[1]], null, true) === $this->data[$this->alias][$fields[0]];
			}

			return $this->data[$this->alias][$fields[0]] === $this->data[$this->alias][$fields[1]];
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
		public $tablePrefix = 'core_';
	}