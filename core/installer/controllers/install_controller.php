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

	Configure::write('Session.save', 'php');

	class InstallController extends Controller {
		/**
		 * Controller name
		 *
		 * @var string
		 * @access public
		 */
		public $name = 'Install';

		/**
		 * No models required
		 *
		 * @var array
		 * @access public
		 */
		public $uses = array();

		/**
		 * No components required
		 *
		 * @var array
		 * @access public
		 */
		public $components = array('Libs.Wizard', 'Session');

		public $helpers = array('Html', 'Form', 'Libs.Wizard', 'Text');

		private $__phpVersion = '5.0';

		private $__supportedDatabases = array(
			'mysql' => array(
				'name' => 'MySQL',
				'version' => '5.0',
				'versionQuery' => 'select version();',
			 	'function' => 'mysql_connect',
			),
			'mysqli' => array(
				'name' => 'MySQLi',
				'version' => '5.0',
				'versionQuery' => 'select version();',
				'function' => 'mysqli_connect',
			),
			'mssql' => array(
				'name' => 'Microsoft SQL Server',
				'version' => '8.0',
				'versionQuery' => 'SELECT CAST(SERVERPROPERTY(\'productversion\') AS VARCHAR)',
				'function' => 'mssql_connect',
			)
		);

		private $__requiredExtensions = array(
			'zlib' => 'Zlib is required for some of the functionality in Infinitas'
		);

		private $__recommendedIniSettings = array (
			array (
				'setting' => 'safe_mode',
				'recomendation' => 0,
				'desc' => 'This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0'
			),
			array (
				'setting' => 'display_errors',
				'recomendation' => 1,
				'desc' => 'Infinitas will handle errors througout the app'
			),
			array (
				'setting' => 'file_uploads',
				'recomendation' => 1,
				'desc' => 'File uploads are needed for the wysiwyg editors and system installers'
			),
			array (
				'setting' => 'magic_quotes_runtime',
				'recomendation' => 0,
				'desc' => 'This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0. Relying on this feature is highly discouraged.'
			),
			array (
				'setting' => 'register_globals',
				'recomendation' => 0,
				'desc' => 'This feature has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0. Relying on this feature is highly discouraged.'
			),
			array (
				'setting' => 'output_buffering',
				'recomendation' => 0,
				'desc' => 'Infinitas will handle output_buffering for you throughout the app'
			),
			array (
				'setting' => 'session.auto_start',
				'recomendation' => 0,
				'desc' => 'Sessions are completly handled by Infinitas'
			),
		);

		private $__paths;

		public $installerProgress = array();

		/**
		 * beforeFilter
		 *
		 * @return void
		 */
		public function beforeFilter() {
			parent::beforeFilter();

			$this->__paths = array(
				'config/database.php' => array(
					'type' => 'write',
					'message' => 'The database configuration (%s) file should be writable during the ' .
						'installation process. It should be set to be read-only once Infinitas has been installed.'
				),
				'tmp' => array(
					'type' => 'write',
					'message' => 'The temporary directory (%s) should be writable by Infinitas. Caching ' .
						'will not work otherwise and Infinitas will perform very poorly.',
				)
			);

			$this->layout = 'installer';

			$this->view = 'View';

			$this->helpers[] = 'Html';

			$this->sql = array(
				'core_data' => APP . 'infinitas' . DS . 'installer' . DS . 'config' . DS . 'schema' . DS . 'infinitas_core_data.sql',
				'core_sample_data' => APP . 'infinitas' . DS . 'installer' . DS . 'config' . DS . 'schema' . DS . 'infinitas_sample_data.sql',
			);


			$this->Wizard->wizardAction = 'index';

			$this->Wizard->steps = array(
				'welcome',
				'database',
				'install',
				'admin_user',
			);

			$this->installerProgress = array(
				'welcome' => __('Welcome', true),
				'database' => __('Database configuration', true),
				'install' => __('Ready to install', true),
				'admin_user' => __('Site administrator', true),
			);

			$this->Wizard->completeUrl = array('plugin' => 'installer', 'controller' => 'install', 'action' => 'finish');

			$this->set('installerProgress', $this->installerProgress);
		}

		public function index($step = null) {
			if(filesize(APP.'config'.DS.'database.php') > 0 && $this->Session->read('installing') == false) {
				$this->notice(
					__('Infinitas has already been installed', true),
					array(
						'redirect' => true
					)
				);
			}

			$this->Session->write('installing', true);
			$this->set('title_for_layout', $this->installerProgress[($step == null) ? 'welcome' : $step]);
			$this->Wizard->process($step);
		}

		public function finish() {
			$this->Session->write('installing', false);
			$this->set('title_for_layout', 'Finished');
			$this->set('hidePrevious', true);
			$this->set('hideNext', true);
		}

		/**
		 * Wizard prepare steps methods
		 */
		public function _prepareWelcome() {
			$core = $this->__checkCore();
			$paths = $this->__checkPaths();
			$database = $this->__checkDatabases(true);
			$recomendations = $this->__checkIniSettings();

			$this->set(compact('core', 'database', 'paths', 'recomendations'));
			$this->set('supportedDb', $this->__supportedDatabases);
		}

		public function _prepareDatabase() {
			$this->loadModel('Installer.Install');
			$database = $this->__checkDatabases();

			$this->set(compact('database'));
		}

		public function _prepareInstall() {}

		public function _prepareAdminUser() {
			if(!is_readable(APP . 'config' . DS . 'database.php') || filesize(APP . 'config' . DS . 'database.php') == 0) {
				$this->Session->delete('Wizard');
				$this->Session->setFlash(__('There was an unrecoverable error configuring your database. Unfortunately you need to restart the installation process. You may need to clear any tables that have been created', true));
				$this->redirect('/');
				exit;
			}

			$this->loadModel('Management.User');

			$this->set('hidePrevious', true);
		}

		/**
		 * Wizard process step methods
		 */
		public function _processWelcome() {
			return true;
		}

		public function _processDatabase() {
			App::import('Model', 'Installer.Install');

			$install = new Install(false, false, false);

			$install->set($this->data);
			if($install->validates() && $this->__testConnection()) {
				return true;
			}

			return false;
		}

		public function _processInstall() {
			if($this->__installPlugins() && $this->__writeDbConfig()) {
				return true;
			}

			return false;
		}

		public function _processAdminUser() {
			$this->loadModel('Management.User');

			$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
			$this->data['User']['ip_address'] = env('REMOTE_ADDR');
			$this->data['User']['browser'] = env('HTTP_USER_AGENT');
			$this->data['User']['operating_system'] = '';
			$this->data['User']['country'] = '';
			$this->data['User']['city'] = '';
			$this->data['User']['group_id'] = 1;
			$this->data['User']['active'] = 1;

			if($this->User->save($this->data)) {
				return true;
			}

			return false;
		}
		/**
		 * Private methods
		 */

		/**
		 *
		 * @return array
		 */
		private function __checkCore() {
			$core = array();

			if(phpversion() < $this->__phpVersion) {
				$core[] = sprintf(__('PHP version %s detected, %s needs at least version %s.', true), phpversion(), 'Infinitas', $this->__phpVersion.'.x');
			}

			foreach($this->__requiredExtensions as $extension => $message) {
				if(!extension_loaded($extension)) {
					$core[] = __($message);
				}
			}

			return $core;
		}

		/**
		 *
		 * @return array
		 */
		private function __checkPaths() {
			$paths = array();

			foreach($this->__paths as $path => $options) {
				switch($options['type']) {
					case 'write':
						$function = 'is_writable';
						break;
					default:
						$function = 'is_readable';
				}
				if(!$function(APP.$path)) {
					$paths[] = sprintf(__($options['message'], true), APP.$path);
				}
			}

			return $paths;
		}

		/**
		 *
		 * @return array
		 */
		private function __checkDatabases($databaseSupported = false) {
			$availableDb = array();

			foreach($this->__supportedDatabases as $cakeType => $supportedDatabase) {
				if(function_exists($supportedDatabase['function'])) {
					$availableDb[$cakeType] = sprintf(__('%s (Version %s or newer)', true), $supportedDatabase['name'], $supportedDatabase['version']);;
				}
			}

			return ($databaseSupported == true) ? !empty($availableDb) : $availableDb;
		}

		/**
		 *
		 * @return array
		 */
		private function __checkIniSettings() {
			$recomendations = array();

			foreach($this->__recommendedIniSettings as $k => $setting) {
				if((int)ini_get($setting['setting']) !== $setting['recomendation']) {
					$setting['current'] = (int)ini_get($setting['setting']);
					$setting['desc'] = __($setting['desc'], true);
					$recomendations[] = $setting;
				}
			}

			return $recomendations;
		}

		private function __cleanConnectionDetails($adminUser = false) {
			$connectionDetails = $this->data['Install'];
			unset($connectionDetails['step']);

			if(trim($connectionDetails['port']) == '') {
				unset($connectionDetails['port']);
			}

			if(trim($connectionDetails['prefix']) == '') {
				unset($connectionDetails['prefix']);
			}

			if($adminUser == true) {
				$connectionDetails['login'] = $this->data['Admin']['username'];
				$connectionDetails['password'] = $this->data['Admin']['password'];
			}

			return $connectionDetails;
		}

		private function __testConnection() {
			$connectionDetails = $this->__cleanConnectionDetails();
			$adminConnectionDetails = $this->__cleanConnectionDetails(true);

			if(!@ConnectionManager::create('installer', $connectionDetails)->isConnected()) {
				$this->set('dbError', true);
				return false;
			}

			else {
				if(trim($adminConnectionDetails['login']) != '') {
					if(!@ConnectionManager::create('admin', $adminConnectionDetails)->isConnected()) {
						$this->set('adminDbError', true);
						return false;
					}
				}

				$dbOptions = $this->__supportedDatabases[$connectionDetails['driver']];
				$version = ConnectionManager::getDataSource('installer')->query($dbOptions['versionQuery']);
				$version = $version[0][0]['version()'];

				if(version_compare($version, $dbOptions['version']) >= 0) {
					return true;
				}
				else {
					$this->set('versionError', $version);
					$this->set('requiredDb', $dbOptions);
					return false;
				}
			}
		}

		private function __writeDbConfig() {
			$dbConfig = $this->Wizard->read('database.Install');

			copy(App::pluginPath('Installer') . 'config' . DS . 'database.install', APP . 'config' . DS . 'database.php');

			App::import('Core', 'File');
			$file = new File(APP . 'config' . DS . 'database.php', true);
			$content = $file->read();

			$find = array(
				'{default_driver}',
				'{default_host}',
				'{default_login}',
				'{default_password}',
				'{default_database}',
				'{default_prefix}',
				'{default_port}',
			);

			$replacements = array(
				$dbConfig['driver'],
				$dbConfig['host'],
				$dbConfig['login'],
				$dbConfig['password'],
				$dbConfig['database'],
				$dbConfig['prefix'],
				$dbConfig['port'],
			);

			$content = str_replace($find, $replacements, $content);

			if ($file->write($content)) {
				return true;
			}
			return false;
		}

		private function __installPlugins() {
			$this->data = array_merge($this->data, $this->Wizard->read('database'));

			App::import('Core', 'ConnectionManager');
			if($this->data['Admin']['username'] !== '') {
				$dbConfig = $this->__cleanConnectionDetails(true);
			}

			else {
				$dbConfig = $this->__cleanConnectionDetails();
			}

			$db = ConnectionManager::create('default', $dbConfig);

			$plugins = App::objects('plugin');
			natsort($plugins);

			App::import('Lib', 'Installer.ReleaseVersion');
			$Version = new ReleaseVersion();

			//Install app tables first
			$result = $this->__installPlugin($Version, $dbConfig, 'app');

			$result = true;
			if($result) {
				//Then install the Installer plugin
				$result = $result && $this->__installPlugin($Version, $dbConfig, 'Installer');

				if($result) {
					//Then install all other plugins
					foreach($plugins as $plugin) {
						if($plugin != 'Installer') {
							$result = $result && $this->__installPlugin($Version, $dbConfig, $plugin);
						}
					}
				}
			}

			return $result;
		}

		private function __installPlugin(&$Version, $dbConfig, $plugin = 'app') {
			if($plugin !== 'app') {
				$pluginPath = App::pluginPath($plugin);
				$configPath = $pluginPath . 'config' . DS;
				$checkFile = $configPath . 'config.json';
			}

			else {
				$configPath = APP . 'config' . DS;
				$checkFile = $configPath . 'releases' . DS . 'map.php';
			}

			if(file_exists($checkFile)) {
				if(file_exists($configPath . 'releases' . DS . 'map.php')) {
					try {
						$mapping = $Version->getMapping($plugin);

						$latest = array_pop($mapping);

						$versionResult = $Version->run(array(
							'type' => $plugin,
							'version' => $latest['version'],
							'basePrefix' => (isset($dbConfig['prefix']) ? $dbConfig['prefix'] : ''),
							'sample' => $this->data['Sample']['sample'] == 1
						));
					}

					catch (Exception $e) {
						return false;
					}
				}

				else {
					$versionResult = true;
				}

				if($versionResult && $plugin !== 'app') {
					$this->loadModel('Installer.Plugin');

					return $this->Plugin->installPlugin($plugin, array('installRelease' => false));
				}

				return $versionResult;
			}

			return true;
		}
	}