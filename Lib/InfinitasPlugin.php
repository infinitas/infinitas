<?php
	class InfinitasPlugin extends CakePlugin {
		private static $__plugins = array(
			'all' => null,
			'core' => null,
			'nonCore' => null,
			'installed' => null,
			'notInstalled' => null,
			'loaded' => null,
			'notLoaded' => null
		);
		
		private static $__corePath = null;
		
		private static $__pluginPaths = false;
		
		public static function corePath() {
			return APP . 'Core';
		}
		
		public static function loadCore() {
			self::__findPlugins();
			
			foreach(self::listPlugins('core') as $plugin) {
				self::load($plugin);
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
			
			CakePlugin::load($plugin, $config);
			try {
				if(Configure::read($plugin) === null) {
					Configure::load($plugin . '.config');
				}
			}
			catch(ConfigureException $e) {
				CakeLog::write('exception', $e->getMessage());
			}
			
			App::uses($plugin . 'AppModel', $plugin . '.Model');
			App::uses($plugin . 'AppController', $plugin . '.Controller');
		}

		/**
		 * @brief find plugins by the various statuses that they can have.
		 * 
		 * see self::$__plugins for the different states plugins can be in
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
					if(self::$__plugins[$type] === null) {
						self::$__plugins[$type] = ClassRegistry::init('Installer.Plugin')->getInstalledPlugins();
						natsort(self::$__plugins[$type]);
					}
					break;
					
				case 'notInstalled':
					if(self::$__plugins[$type] === null) {
						self::$__plugins[$type] = array_diff(
							self::__listPlugins('all'), 
							array_values(self::__listPlugins('installed'))
						);
						natsort(self::$__plugins[$type]);
					}
					break;
					
				case 'loaded':
					if(self::$__plugins[$type] === null) {
						self::$__plugins[$type] = ClassRegistry::init('Installer.Plugin')->getActiveInstalledPlugins();
						natsort(self::$__plugins[$type]);
					}
					break;
					
				case 'nonLoaded':
					if(self::$__plugins[$type] === null) {
						self::$__plugins[$type] = array_diff(
							self::__listPlugins('loaded'), 
							array_values(ClassRegistry::init('Installer.Plugin')->getActiveInstalledPlugins())
						);
						natsort(self::$__plugins[$type]);
					}
					break;
			}
			
			return self::$__plugins[$type];
		}
		

		/**
		* @brief get all the places plugins are found and tell cake about them
		*
		* Get the paths out of cache if there are any or get them with Folder::read
		* They are used with App::build() to make any extra folders  in APP be plugin
		* folders. This can help if you want to keep plugins outside of /plugins
		*/
		private static function __findPlugins() {
			if(is_array(self::$__pluginPaths)) {
				return true;
			}
			
			self::$__pluginPaths = Cache::read('plugin_paths');
			
			if(self::$__pluginPaths === false) {
				$Folder = new Folder(APP);
				$folders = $Folder->read();
				$folders = array_flip($folders[0]);
				unset($Folder, $folders['.git'], $folders['Config'], $folders['locale'],
					$folders['nbproject'], $folders['Console'], $folders['tmp'], $folders['View'],
					$folders['Controller'],  $folders['Lib'], $folders['webroot'], $folders['Test'],
					$folders['Model']);

				self::$__pluginPaths = array();
				foreach(array_flip($folders) as $folder){
					self::$__pluginPaths[] = APP . $folder . DS;
				}

				Cache::write('plugin_paths', self::$__pluginPaths);
				unset($Folder, $folders);

				// @todo trigger event to get oter plugin paths
			}

			return App::build(array('Plugin' => self::$__pluginPaths));
		}
	}