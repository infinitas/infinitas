<?php
	class Plugin extends InstallerAppModel {
		public $installError = array();
		
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'id' => array(
					'uuid' => array(
						'rule' => 'uuid',
						'required' => true,
						'message' => __d('installer', 'All plugins are required to have a valid unique identifier based on the RFC4122 definition.', true)
					),
				),
				'internal_name' => array(
					'unique' => array(
						'rule' => 'isUnique',
						'message' => __d('installer', 'You already have a plugin with this name installed', true)
					)
				)
			);
		}

		/**
		 * @brief get a list of all the plugins that are in the system
		 *
		 * This will not check plugins that are installed, but anything within
		 * any of the defined plugin directories.
		 *
		 * @access public
		 *
		 * @param string $type list / count
		 *
		 * @return array all plugins in alphabetical order
		 */
		public function getAllPlugins($type = 'list'){
			$plugins = App::objects('plugin');
			natsort($plugins);

			if($type == 'count'){
				return count($plugins);
			}

			return $plugins;
		}

		/**
		 * @brief get a list of plugins that have been installed on Infinitas
		 *
		 * @access public
		 *
		 * @param string $type list / count / all
		 *
		 * @return array list of all the installed plugins
		 */
		public function getInstalledPlugins($type = 'list'){
			if(!in_array($type, array('list', 'count', 'all'))){
				$type = 'list';
			}

			$fields = array(
				$this->alias . '.id',
				$this->alias . '.internal_name'
			);
			
			if($type == 'all'){
				$fields = array();
			}
			
			return $this->find(
				$type,
				array(
					'fields' => $fields
				)
			);
		}

		/**
		 * @brief get a list of active installed plugins
		 *
		 * This is used in things like the EventCore to know where to trigger
		 * events
		 *
		 * @param string $type the type of find to do
		 *
		 * @return mixed depends on the type passed in could be int, array list or full find
		 */
		public function getActiveInstalledPlugins($type = 'list'){
			if(!in_array($type, array('list', 'count', 'all'))){
				$type = 'list';
			}

			$fields = array(
				$this->alias . '.id',
				$this->alias . '.internal_name'
			);

			if($type == 'all'){
				$fields = array();
			}

			return $this->find(
				$type,
				array(
					'fields' => $fields,
					'conditions' => array(
						$this->alias . '.active' => 1
					)
				)
			);
		}

		/**
		 * @brief method to find a list of plugins not yet installed
		 *
		 * This uses the getAllPlugins() method and getInstalledPlugins() method
		 * with array_diff to show a list of plugins that have not yet been installed
		 *
		 * @access public
		 *
		 * @param string $type list / count
		 *
		 * @return array list of plugins not yet installed
		 */
		public function getNonInstalledPlugins($type = 'list'){
			$nonInstalled = array_diff($this->getAllPlugins(), array_values($this->getInstalledPlugins()));
			natsort($nonInstalled);

			if($type == 'count'){
				return count($nonInstalled);
			}
			
			return $nonInstalled;
		}

		/**
		 * @brief install a plugin
		 *
		 * This method is used to install a plugin and record the details in the
		 * plugin table. There are options that can be used for installing your
		 * own created plugin or a plugin you have downloaded.
		 *
		 * $options can be the following
		 *  - sampleData true/false to install any sample data
		 *  - installRelease true/fase (true will run the fixtures, false will only save the details so it is officially installed)
		 *
		 * @access public
		 *
		 * @param string $pluginName the name of the plugin being installed
		 * @param array $options the options for the install
		 *
		 * @return bool true if installed, false if not.
		 */
		public function installPlugin($pluginName, $options = array()) {
			$options = array_merge(
				array(
					'sampleData' => false,
					'installRelease' => true
				),
				$options
			);

			$pluginDetails = $this->__loadPluginDetails($pluginName);

			if($pluginDetails !== false && isset($pluginDetails['id'])) {
				$pluginDetails['dependancies'] = json_encode($pluginDetails['dependancies']);
				$pluginDetails['internal_name'] = $pluginName;
				$pluginDetails['enabled'] = true;
				$pluginDetails['core'] = strpos(App::pluginPath($pluginName), APP . 'core' . DS) !== false;

				$pluginDetails['license'] = !empty($pluginDetails['license']) ? $pluginDetails['license'] : $pluginDetails['author'] . ' (c)';

				$installed = false;
				$this->create();
				if($this->save(array($this->alias => $pluginDetails))) {
					$installed = true;
					
					if($options['installRelease'] === true) {
						$installed = $this->__processRelease($pluginName, $options['sampleData']);
					}
				}

				return $installed;
			}
		}

		/**
		 * @brief load up the details from the plugins config file
		 *
		 * This will attempt to get the plugins details so that they can be saved
		 * to the database.
		 * 
		 * If the plugin has not had a release created then it will return false
		 * and will not be able to be saved.
		 *
		 * @param string $pluginName the name of the plugin to load
		 *
		 * @return array the details found or false if not found
		 */
		private function __loadPluginDetails($pluginName) {
			$configFile = App::pluginPath($pluginName) . 'config' . DS . 'config.json';

			return file_exists($configFile) ? Set::reverse(json_decode(file_get_contents($configFile))) : false;
		}

		/**
		 * @brief run a plugins release (create tables, update schema etc)
		 *
		 * This runs the migrations part of a plugin install. It will run all
		 * the migrations found. If something goes wrong it will return false
		 *
		 * @access public
		 *
		 * @param string $pluginName the name of the plugin to process
		 * @param bool $sampleData true to install sample data, false if not
		 *
		 * @return mixed false on fail, true on success
		 */
		private function __processRelease($pluginName, $sampleData = false) {
			App::import('Lib', 'Installer.ReleaseVersion');

			try {
				$Version = new ReleaseVersion();
				$mapping = $Version->getMapping($pluginName);
				$latest = array_pop($mapping);

				return $Version->run(
					array(
						'type' => $pluginName,
						'version' => $latest['version'],
						'sample' => $sampleData
					)
				);
			}
			
			catch(Exception $e) {
				$this->installError[] = array(
					'version' => $e->migration['info']['version'],
					'message' => $e->xdebug_message
				);
				
				return false;
			}

			return true;
		}

		/**
		 * @breif get the current installed version of the migration
		 *
		 * @access public
		 *
		 * @param string $plugin the name of the plugin to check
		 *
		 * @return mixed false on error, null if not found or int as version number
		 */
		public function getMigrationVersion($plugin = null){
			if(!$plugin){
				return false;
			}

			$migration = ClassRegistry::init('SchemaMigration')->find(
				'first',
				array(
					'conditions' => array(
						'SchemaMigration.type' => $plugin
					),
					'order' => array(
						'SchemaMigration.version' => 'desc'
					)
				)
			);

			return (isset($migration['SchemaMigration']['version'])) ? $migration['SchemaMigration']['version'] : null;
		}

		/**
		 * @breif get the current version for the passed in plugin
		 *
		 * @access public
		 *
		 * @param string $plugin the name of the plugin
		 *
		 * @return mixed false on error, null if not found int of migrations available
		 */
		public function getAvailableMigrationsCount($plugin = null){
			if(!$plugin){
				return false;
			}

			$path = App::pluginPath($plugin) . 'config' . DS . 'releases';
			$Folder = new Folder($path);

			$data = $Folder->read();
			unset($Folder, $plugin, $path);
			
			if(in_array('map.php', $data[1])){
				return count($data[1]) - 1;
			}

			return null;
		}

		/**
		 * @brief get migrations / installed count for a specific plugin
		 *
		 * @access public
		 *
		 * @param string $plugin the name of the plugin.
		 *
		 * @return mixed array of data or false
		 */
		public function getMigrationStatus($plugin = null){
			if(!$plugin){
				return false;
			}

			$return = array();
			$return['migrations_available'] = $this->getAvailableMigrationsCount($plugin);
			$return['migrations_installed'] = $this->getMigrationVersion($plugin);
			$return['migrations_behind'] = $return['migrations_available'] - $return['migrations_installed'];
			$return['installed'] = $this->isInstalled($plugin);

			return $return;
		}

		/**
		 * @brief check if a plugin is installed
		 *
		 * @access public
		 *
		 * @param string $plugin the name of the plugin
		 *
		 * @return bool true if its installed, false if not
		 */
		public function isInstalled($plugin = null){
			if(!$plugin){
				return false;
			}

			return (bool)$this->find('count', array('conditions' => array($this->alias . '.internal_name' => $plugin)));
		}
	}