<?php
	class Plugin extends InstallerAppModel {
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


				$installed = false;
				if($this->save($pluginDetails)) {
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
				return false;
			}

			return true;
		}
	}