<?php
App::uses('FolderSymlink', 'Filemanager.Utility');
App::uses('ReleaseVersion', 'Installer.Lib');

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
 *
 * @var array
 */
	private static $_pluginPaths = null;

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
		self::_findPlugins();

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
		self::_findPlugins();

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
 * Check if the given name is of a plugin
 *
 * There may be a folder that is in the correct place for a plugin but is not an
 * actual plugin (such as unititialised git submodules)
 *
 * @param string $plugin the plugins name
 *
 * @return boolean
 */
	public static function isPlugin($plugin) {
		try {
			return (bool)self::config($plugin);
		} catch (Exception $e) {}

		return false;
	}

/**
 * Check if the plugin is already installed
 *
 * @param string $plugin the plugin name
 *
 * @return boolean
 */
	public static function isInstalled($plugin) {
		return ClassRegistry::init('Installer.Plugin')->isInstalled($plugin);
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
					self::$__plugins[$type] = App::objects('plugins', self::$_pluginPaths, false);
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
				try {
					if(self::infinitasInstalled() && self::$__plugins[$type] === null) {
						self::$__plugins[$type] = ClassRegistry::init('Installer.Plugin')->getInstalledPlugins();
						natsort(self::$__plugins[$type]);
					}
				}
				catch(Exception $e) {
					self::$__plugins[$type] = array();
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
				try {
					if(self::infinitasInstalled() && self::$__plugins[$type] === null) {
						self::$__plugins[$type] = ClassRegistry::init('Installer.Plugin')->getActiveInstalledPlugins();
						natsort(self::$__plugins[$type]);
					}
				}
				catch(Exception $e) {
					self::$__plugins[$type] = array();
				}
				break;

			case 'notLoaded':
				try {
					if(self::infinitasInstalled() && self::$__plugins[$type] === null) {
						self::$__plugins[$type] = array_diff(
							array_values(ClassRegistry::init('Installer.Plugin')->getActiveInstalledPlugins()),
							parent::loaded()
						);
						natsort(self::$__plugins[$type]);
					}
				}
				catch(Exception $e) {
					self::$__plugins[$type] = array();
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
	protected static function _findPlugins() {
		if(self::$_pluginPaths) {
			return true;
		}

		self::$_pluginPaths = Cache::read('plugin_paths');
		if(!is_array(self::$_pluginPaths)) {
			$Folder = new Folder(APP);
			$folders = $Folder->read();
			$folders = array_flip($folders[0]);
			unset($Folder, $folders['.git'], $folders['Config'], $folders['Locale'],
				$folders['nbproject'], $folders['Console'], $folders['tmp'], $folders['View'],
				$folders['Controller'],  $folders['Lib'], $folders['webroot'], $folders['Test'],
				$folders['Model'], $folders['CakePHP']);

			self::$_pluginPaths = array();
			foreach(array_flip($folders) as $folder) {
				self::$_pluginPaths[] = APP . $folder . DS;
			}

			Cache::write('plugin_paths', self::$_pluginPaths);
			unset($Folder, $folders);

			// @todo trigger event to get oter plugin paths
		}

		return App::build(array('Plugin' => self::$_pluginPaths));
	}

/**
 * Install a plugin
 *
 * This method runs the release to create / modify the db and then activates the
 * plugin.
 *
 * @param string $plugin the name of the plugin to install
 * @param array $options the options for the install
 *
 * @return boolean
 */
	public static function install($plugin, array $options = array()) {
		if(self::runRelease($plugin, $options) === false) {
			return false;
		}

		return self::activate($plugin);
	}

/**
 * Run a plugins release
 *
 * If the release should not be run or is not specified null will be returned.
 * False is returned if there was a problem, and true when all was run correctly.
 *
 * Options:
 *	- sampleData: boolean true for installing sample data, false for not
 *	- installRelease: boolean true to run the release, false if not
 *
 * @param string $plugin the name of the plugin to install
 * @param array $options the options for the install
 *
 * @return null|boolean
 */
	public static function runRelease($plugin, array $options) {
		if(!array_key_exists('installRelease', $options) || !$options['installRelease']) {
			return null;
		}

		$Version = new ReleaseVersion();
		$mapping = $Version->getMapping($plugin);
		$latest = array_pop($mapping);

		return $Version->run(array(
			'type' => $plugin,
			'version' => $latest['version'],
			'sample' => !empty($options['sampleData']) ? (bool)$options['sampleData'] : false
		));
	}

/**
 * Activate a plugin
 *
 * Preform tasks that are required to make a plugin available
 *
 * @param string $plugin the plugin name
 *
 * @return boolean
 */
	public static function activate($plugin) {
		$FolderSymlink = new FolderSymlink();

		$pluginWebroot = InfinitasPlugin::path($plugin) . 'webroot' . DS;
		$pluginAsset = Inflector::underscore($plugin);
		if(is_dir($pluginWebroot) && !is_dir(getcwd() . DS . $pluginAsset)) {
			$FolderSymlink->create(WWW_ROOT . $pluginAsset, $pluginWebroot);
		}
		return true;
	}

/**
 * Uninstall a plugin
 *
 * This will remove all the plugins data and unlink the webroot folder. This is
 * the opposit of InfinitasPlugin::install()
 *
 * @param string $plugin the name of the plugin to uninstall
 *
 * @return boolean
 */
	public static function uninstall($plugin) {

		return self::deactivate($plugin);
	}

/**
 * Deactivate a plugin
 *
 * When a plugin is not active, remove the links so its files are not accesible
 * via the web. This is the opposit of InfinitasPlugin::activate()
 *
 * @param string $plugin the name of the plugin to deactivate
 *
 * @return boolean
 */
	public static function deactivate($plugin) {
		$FolderSymlink = new FolderSymlink();
		$FolderSymlink->delete(WWW_ROOT . Inflector::underscore($plugin));

		return true;
	}

/**
 * load up the details from the plugins config file
 *
 * This will attempt to get the plugins details so that they can be saved
 * to the database.
 *
 * If the plugin has not had a release created then it will return false
 * and will not be able to be saved.
 *
 * @param string $pluginName the name of the plugin to load
 *
 * @return array
 */
	public static function config($pluginName) {
		$configFile = CakePlugin::path($pluginName) . 'Config' . DS . 'config.json';
		if(!is_file($configFile)) {
			throw new InstallerConfigurationException(array($pluginName, 'missing'));
		}
		$File = new File($configFile);
		$File = Set::reverse(json_decode($File->read(), true));
		if(empty($File)) {
			throw new InstallerConfigurationException(array($pluginName, 'invalid'));
		}

		return $File;
	}

}