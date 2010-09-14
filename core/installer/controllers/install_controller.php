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
		var $name = 'Install';

		/**
		* No models required
		*
		* @var array
		* @access public
		*/
		var $uses = array();

		/**
		* No components required
		*
		* @var array
		* @access public
		*/
		var $components = array('Libs.Wizard', 'DebugKit.Toolbar');
		var $helpers = array('Libs.Wizard');

		private $__phpVersion = '5.0';

		private $__supportedDatabases = array(
			'mysql' => array(
				'name' => 'MySQL',
				'version' => '5.0',
				'versionQuery' => 'select version();',
			 	'function' => 'mysql_connect',
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

		var $installerProgress = array();

		/**
		* beforeFilter
		*
		* @return void
		*/
		function beforeFilter() {
			parent::beforeFilter();
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
				'install' => __('Installation', true),
				'admin_user' => __('Site administrator', true),
			);

			$this->Wizard->completeUrl = '/installer/install/finish';
			
			$this->set('installerProgress', $this->installerProgress);
		}

		function index($step = null) {
			$this->set('title_for_layout', $this->installerProgress[$step == null ? 'welcome' : $step]);
			$this->Wizard->process($step);
		}

		function finish() {
			
		}

/**
 * Wizard prepare steps methods
 */
		function _prepareWelcome() {
			$core = $this->__checkCore();
			$paths = $this->__checkPaths();
			$database = $this->__checkDatabases(true);
			$recomendations = $this->__checkIniSettings();

			$this->set(compact('core', 'database', 'paths', 'recomendations'));
			$this->set('supportedDb', $this->__supportedDatabases);
		}

		function _prepareDatabase() {
			$database = $this->__checkDatabases();

			$this->set(compact('database'));
		}

		function _prepareInstall() {
			$plugins = App::objects('plugin');

			$availablePlugins = array(
				'core' => array(),
				'plugin' => array()
			);

			foreach($plugins as $plugin) {
				$pluginPath = App::pluginPath($plugin);

				if(strpos($pluginPath, APP . 'core') !== false && file_exists($pluginPath . 'config' . DS . 'config.json')) {
					$availablePlugins['core'][$plugin] = $this->__loadPluginDetails($pluginPath);
				}
				elseif(strpos($pluginPath, APP.'plugins') !== false && file_exists($pluginPath . 'config' . DS . 'config.json')) {
					$availablePlugins['plugin'][$plugin] = $pluginPath;
				}
			}

			$this->set('availablePlugins', $availablePlugins);
		}

		function _prepareAdminUser() {
			$this->loadModel('Management.User');
		}

/**
 * Wizard process step methods
 */
		function _processWelcome() {
			return true;
		}

		function _processDatabase() {
			App::import('Model', 'Installer.Install');

			$install = new Install(false, false, false);
			
			$install->set($this->data);
			if($install->validates() && $this->__testConnection()) {
				return true;
			}

			return false;
		}

		function _processInstall() {
			if($this->__installPlugins() && $this->__writeDbConfig()) {
				return true;
			}

			return false;
		}

		function _processAdminUser() {
			$this->loadModel('Management.User');

			$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
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

		private function __loadPluginDetails($pluginPath) {
			return Set::reverse(json_decode(file_get_contents($pluginPath . 'config' . DS . 'config.json')));
		}

		/**
		 *
		 * @return array
		 */
		private function __checkCore() {
			$core = array();

			if(phpversion() < $this->__phpVersion) {
				$core[] = sprintf(__('PHP version %s detected, %s needs at least version %s.', true), phpversion(), 'Infinitas', $this->phpVersion.'.x');
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

			foreach($paths as $path => $options) {
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
		}

		/**
		 *
		 * @return array
		 */
		private function __checkDatabases($databaseSupported = false) {
			$availableDb = array();

			foreach($this->__supportedDatabases as $cakeType => $supportedDatabase) {
				if(function_exists($supportedDatabase['function'])) {
					$availableDb[$cakeType] = $supportedDatabase['name'] . ' (Version ' . $supportedDatabase['version'] . ' or newer)';
				}
			}

			return $databaseSupported == true ? !empty($availableDb) : $availableDb;
		}

		/**
		 *
		 * @return array
		 */
		private function __checkIniSettings() {
			$recomendations = array();

			foreach($this->__recommendedIniSettings as $k => $setting) {
				if((int)ini_get($setting['setting']) !== $setting['recomendation']) {
					$recomendations[] = $setting;
				}
			}

			return $recomendations;
		}

		function __cleanConnectionDetails() {
			$connectionDetails = $this->data['Install'];
			unset($connectionDetails['step']);

			if(trim($connectionDetails['port']) == '') {
				unset($connectionDetails['port']);
			}

			if(trim($connectionDetails['prefix']) == '') {
				unset($connectionDetails['prefix']);
			}

			return $connectionDetails;
		}

		function __testConnection() {
			$connectionDetails = $this->__cleanConnectionDetails();

			if(!@ConnectionManager::create('installer', $connectionDetails)->isConnected()) {
				$this->set('dbError', true);
				return false;
			}
			else {
				$dbOptions = $this->__supportedDatabases[$connectionDetails['driver']];
				$version = ConnectionManager::getDataSource('installer')->query($dbOptions['versionQuery']);
				$version = $version[0][0]['version()'];

				if(version_compare($version, $dbOptions['version']) >= 0) {
					return true;
				}
				else {
					$this->set('versionError', $version);
					$this->set('requiredVersion', $dbOptions['version']);
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
				'{default_host}',
				'{default_login}',
				'{default_password}',
				'{default_database}',
				'{default_prefix}',
				'{default_port}',
			);

			$replacements = array(
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
			$dbConfig = $this->Wizard->read('database.Install');
			unset($dbConfig['step']);

			if(trim($dbConfig['port']) == '') {
				unset($dbConfig['port']);
			}

			if(trim($dbConfig['prefix']) == '') {
				unset($dbConfig['prefix']);
			}

			App::import('Core', 'ConnectionManager');
			$db = ConnectionManager::create('default', $dbConfig);

			$plugins = App::objects('plugin');
			natsort($plugins);

			App::import('Lib', 'Installer.ReleaseVersion');
			$Version = new ReleaseVersion();

			$result = true;

			//Install app tables first
			$this->__installPlugin($Version, 'app');
			
			foreach($plugins as $plugin) {
				$result = $result && $this->__installPlugin($Version, $plugin);
			}

			return $result;
		}

		private function __installPlugin(&$Version, $plugin = 'app') {
			if($plugin !== 'app') {
				$pluginPath = App::pluginPath($plugin);
				$checkFile = $pluginPath . 'config' . DS . 'config.json';
			}
			else {
				$checkFile = APP . 'config' . DS . 'releases' . DS . 'map.php';
			}
			
			if(file_exists($checkFile)) {
				$mapping = $Version->getMapping($plugin);

				$latest = array_pop($mapping);

				return $Version->run(array(
					'type' => $plugin,
					'version' => $latest['version'],
					'basePrefix' => (isset($dbConfig['prefix']) ? $dbConfig['prefix'] : ''),
					'sample' => $this->data['sample'] == 1
				));
			}

			return true;
		}

		private function __installCore() {
			ClassRegistry::init('Installer.Release')->installData($this->data['Install']['sample']);

			return true;
		}



/*
		function licence() {
			$this->set('title_for_layout', __('Licence', true));
		}

		function database() {
			$this->set('title_for_layout', __('Database Configuration', true));
			if (!empty($this->data)) {
				if ($this->__testConnection()) {
					copy(APP . 'infinitas' . DS . 'installer' . DS . 'config' . DS . 'database.install', APP . 'config' . DS . 'database.php');

					App::import('Core', 'File');
					$file = new File(APP . 'config' . DS . 'database.php', true);
					$content = $file->read();

					$content = str_replace('{default_host}', $this->data['Install']['host'], $content);
					$content = str_replace('{default_login}', $this->data['Install']['login'], $content);
					$content = str_replace('{default_password}', $this->data['Install']['password'], $content);
					$content = str_replace('{default_database}', $this->data['Install']['database'], $content);
					$content = str_replace('{default_prefix}', $this->data['Install']['prefix'], $content);
					$content = str_replace('{default_port}', $this->data['Install']['port'], $content);

					if ($file->write($content)) {
						//SessionComponent::setFlash(__('Database configuration saved.', true));
						$this->install();
					}
					//SessionComponent::setFlash(__('Could not write database.php file.', true));
				}else {
					//SessionComponent::setFlash(__('That connection does not seem to be valid', true));
				}
			}
		}

		function install() {
			$this->set('title_for_layout', __('Install Database', true));

			App::import('Core', 'File');
			App::import('Model', 'ConnectionManager');

			$db = ConnectionManager::getDataSource('default');

			if (!$db->isConnected()) {
				pr('Could not connect');
				//SessionComponent::setFlash(__('Could not connect to database.', true));
			}
			else {
				// Can be 'app' or a plugin name
				$type = 'app';

				App::import('Lib', 'Migrations.MigrationVersion');
				// All the job is done by MigrationVersion
				$version = new MigrationVersion();

				// Get the mapping and the latest version avaiable
				$mapping = $version->getMapping($type);
				$latest = array_pop($mapping);

				// Run it to latest version
				if($version->run(array('type' => $type, 'version' => $latest['version'])))
				{
					ClassRegistry::init('Installer.Release')->installData($this->data['Install']['sample_data']);

					$this->redirect(array('action' => 'siteConfig'));
				}
				else
				{
					//SessionComponent::setFlash(__('There was an error installing database data.', true));
				}
			}
		}

		function siteConfig() {
			$this->set('title_for_layout', __('Site Configuration', true));
			if (!empty($this->data)) {
				$good = true;
				foreach($this->data['Config'] as $config){
					switch($config['type']){
						case 'bool':
							switch($config['value']) {
								case 1:
									$config['value'] = 'true';
									break;

								default:
									$config['value'] = 'false';
							} // switch
							break;
					} // switch

					$_config['Config'] = $config;
					$good = $good && ClassRegistry::init('Configs.Config')->save($_config);
				}

				if ($good === true) {
					$this->redirect(array('action' => 'done'));
				}
			}

			ClassRegistry::flush();

			$configs = ClassRegistry::init('Configs.Config')->getInstallSetupConfigs();
			$this->set('configs', $configs);
		}

		function done() {
			$this->pageTitle = __('Installation completed successfully', true);

			if (isset($this->params['named']['rename'])) {
				if (is_dir(APP . 'infinitas' . DS . 'installer') && rename(APP . 'infinitas' . DS . 'installer', APP . 'infinitas' . DS . 'installer' . time())) {
					$this->Session->setFlash(__('The instilation folder has been renamed, if you ever need to run installation again just rename it back to installer.', true));
				}else {
					$this->Session->setFlash(__('Could not find the installer directory.', true));
				}
			}
		}*/

		/**
		* Execute SQL file
		*
		* @link http://cakebaker.42dh.com/2007/04/16/writing-an-installer-for-your-cakephp-application/
		* @param object $db Database
		* @param string $fileName sql file
		* @return void
		*/
		function __executeSQLScript($db, $fileName) {
			$statements = file($fileName);
			$status = true;
			$templine = '';
			foreach ($statements as $line) {
				// Skip it if it's a comment
				if (substr($line, 0, 2) == '--' || $line == '')	{
					continue;
				}
				// Add this line to the current segment
				$templine .= $line;
				// If it has a semicolon at the end, it's the end of the query
				if (substr(trim($line), -1, 1) == ';') {
					// Perform the query
					$status = $status && $db->query($templine);
					// Reset temp variable to empty
					$templine = '';
				}
			}
			return $status;
		}

		function path(){
			return dirname(dirname(__FILE__)).DS.'config'.DS;
		}

		function _getFileData($file){
			App::import('File');

			$this->File = new File($this->path().$file);
			return $this->_decompress($this->File->read());
		}

		function _decompress($data){
			return unserialize(gzuncompress(stripslashes($data)));
		}
	}