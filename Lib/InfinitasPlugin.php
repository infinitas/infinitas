<?php
	class InfinitasPlugin extends CakePlugin {
		/**
		 * @brief internal list of various plugins by the current state
		 * 
		 * @var array
		 */
		private static $__plugins = array(
			'all' => null,
			'core' => null,
			'nonCore' => null,
			'installed' => null,
			'notInstalled' => null,
			'loaded' => null,
			'notLoaded' => null
		);
		
		/**
		 * @brief the paths where plugins can be found
		 * @var type 
		 */
		private static $__pluginPaths = false;
		
		/**
		 * @brief get the core path
		 * 
		 * @access public
		 * 
		 * @return string
		 */
		public static function corePath() {
			return APP . 'Core';
		}
		
		/**
		 * @brief load core plugins
		 * 
		 * This loads up plugins that are part of the infinitas core
		 * 
		 * @access public
		 * 
		 * @return void 
		 */
		public static function loadCore() {
			self::__findPlugins();
			
			foreach(self::listPlugins('core') as $plugin) {
				self::load($plugin);
			}
		}
		
		/**
		 * @brief load the user plugins
		 * 
		 * This loads up plugins that are installed and active but not part of 
		 * the core.
		 * 
		 * @access public
		 * 
		 * @return void 
		 */
		public static function loadInstalled() {
			$object = new StdClass();
			
			foreach((array)self::listPlugins('notLoaded') as $plugin) {
				self::load($plugin);
				EventCore::trigger($object, $plugin . '.requireLibs');
				configureCache(EventCore::trigger($object, $plugin . '.setupCache'));
				
				EventCore::loadEventHandler($plugin);
			}
		}
		
		/**
		 * @brief load plugins for the installer
		 * 
		 * The installer needs to know about everything in the system but there
		 * are no routes loaded or configs
		 * 
		 * @access public
		 * 
		 * @return void 
		 */
		public static function loadForInstaller() {
			self::__findPlugins();
			
			foreach(self::listPlugins('all') as $plugin) {
				parent::load($plugin);
			}
		}
		
		/**
		 * @brief load a plugin
		 * 
		 * This overloads the cake method for loading plugins to do various other
		 * bootstrapping that infinitas may need such as loading configs, setting
		 * up cache etc
		 * 
		 * @param string $plugin the plugin to load, can be array of plugins
		 * @param array $config the config to use
		 * 
		 * @return void
		 */
		public static function load($plugin, $config = array()) {
			if(is_array($plugin)) {
				foreach($plugin as $p => $c) {
					if(is_int($p)) {
						$p = $c;
						$c = array();
					}
					
					self::load($p, $c);
				}
			}
			
			if(!in_array($plugin, App::objects('plugin'))) {
				return false;
			}
			
			try {
				CakePlugin::load($plugin, $config);
				if(Configure::read($plugin) === null) {
					Configure::load($plugin . '.config');
				}
			}
			
			catch(ConfigureException $e) {
				CakeLog::write('exception', $e->getMessage());
			}
			
			$exceptions = self::path($plugin) . 'Lib' . DS . 'Error' . DS . 'exceptions.php';
			if(!is_file($exceptions)) {
				CakeLog::write('exceptions', 'No exceptions file found for plugin ' . $plugin);
			}
			else {
				require $exceptions;
			}
			
			App::uses($plugin . 'AppModel', $plugin . '.Model');
			App::uses($plugin . 'AppController', $plugin . '.Controller');

			self::$__plugins['loaded'][] = $plugin;
			
			return true;
		}

		/**
		 * @brief find plugins by the various statuses that they can have.
		 * 
		 * see self::$__plugins for the different states plugins can be in
		 * 
		 * - all: all plugins that are on disk
		 * - core: core plugins, in APP . 'Core'
		 * - nonCore: user added plugins
		 * - installed: plugins tha are installed and usable
		 * - notInstalled: On disk but not available for use
		 * - loaded: installed and loaded
		 * - notLoaded: installed but not loaded
		 * 
		 * @param string $type the plugin type/status to get
		 * 
		 * @return array list of plugins
		 * 
		 * @throws Exception 
		 */
		public static function listPlugins($type = 'installed') {
			if(!in_array($type, array_keys(self::$__plugins))) {
				throw new Exception(sprintf('Invalid type "%s" requested', $type));
			}
			
			switch($type) {
				case 'all':
					if(self::$__plugins[$type] === null) {
						self::$__plugins[$type] = App::objects('plugins', self::$__pluginPaths, false);
					}
					break;
					
				case 'core':
					if(self::$__plugins['core'] === null) {
						self::$__plugins['core'] = new Folder(self::corePath());
						self::$__plugins['core'] = current(self::$__plugins['core']->read());
					}
					break;
					
				case 'nonCore':
					if(self::$__plugins['nonCore'] === null) {
						self::$__plugins['nonCore'] = array();
						
						foreach(self::listPlugins('all') as $plugin) {
							if(!in_array($plugin, self::listPlugins('core'))) {
								self::$__plugins['nonCore'][] = $plugin;
							}
						}
					}
					break;
					
				case 'installed':
					if(self::infinitasInstalled() && self::$__plugins[$type] === null) {
						self::$__plugins[$type] = ClassRegistry::init('Installer.Plugin')->getInstalledPlugins();
						natsort(self::$__plugins[$type]);
					}
					break;
					
				case 'notInstalled':
					if(self::$__plugins[$type] === null) {
						self::$__plugins[$type] = array_diff(
							self::listPlugins('all'), 
							array_values(self::listPlugins('installed'))
						);
						natsort(self::$__plugins[$type]);
					}
					break;
					
				case 'loaded':
					if(self::infinitasInstalled() && self::$__plugins[$type] === null) {
						self::$__plugins[$type] = ClassRegistry::init('Installer.Plugin')->getActiveInstalledPlugins();
						natsort(self::$__plugins[$type]);
					}
					break;
					
				case 'notLoaded':
					if(self::infinitasInstalled() && self::$__plugins[$type] === null) {
						self::$__plugins[$type] = array_diff(
							array_values(ClassRegistry::init('Installer.Plugin')->getActiveInstalledPlugins()),
							parent::loaded()
						);
						natsort(self::$__plugins[$type]);
					}
					break;
			}
			
			return self::$__plugins[$type];
		}
		
		/**
		 * @brief check if infinitas has been installed yet
		 * 
		 * @access public
		 * 
		 * @return bool false if not, true if it has been
		 */
		public static function infinitasInstalled() {
			$databaseConfig = APP . 'Config' . DS . 'database.php';
			
			return file_exists($databaseConfig) && filesize($databaseConfig) > 0;
		}

		/**
		* @brief get all the places plugins are found and tell cake about them
		*
		* Get the paths out of cache if there are any or get them with Folder::read
		* They are used with App::build() to make any extra folders  in APP be plugin
		* folders. This can help if you want to keep plugins outside of /plugins
		*/
		private static function __findPlugins() {
			if(!empty(self::$__pluginPaths)) {
				return true;
			}
			
			self::$__pluginPaths = Cache::read('plugin_paths');
			if(self::$__pluginPaths === false) {
				$Folder = new Folder(APP);
				$folders = $Folder->read();
				$folders = array_flip($folders[0]);
				unset($Folder, $folders['.git'], $folders['Config'], $folders['Locale'],
					$folders['nbproject'], $folders['Console'], $folders['tmp'], $folders['View'],
					$folders['Controller'],  $folders['Lib'], $folders['webroot'], $folders['Test'],
					$folders['Model']);

				self::$__pluginPaths = array();
				foreach(array_flip($folders) as $folder) {
					self::$__pluginPaths[] = APP . $folder . DS;
				}

				Cache::write('plugin_paths', self::$__pluginPaths);
				unset($Folder, $folders);

				// @todo trigger event to get oter plugin paths
			}
			
			return App::build(array('Plugin' => self::$__pluginPaths));
		}
	}