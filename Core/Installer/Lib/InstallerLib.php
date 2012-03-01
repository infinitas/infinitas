<?php
	App::uses('ConnectionManager', 'Model');
	App::uses('File', 'Utility');
	class InstallerLib {
		/**
		 * minimum php version number required to run infinitas
		 * @var <type>
		 */
		private $__phpVersion = '5.0';

		/**
		 * list of databases that infinitas supports
		 * @var <type>
		 */
		private $__supportedDatabases = array(
			'Mysql' => array(
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

		/**
		 * reccomended settings for running infinitas
		 * @var <type>
		 */
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

		/**
		 * paths that infinitas will need to install its self
		 * @var <type>
		 */
		private $__paths = array();

		/**
		 * extentions that are required to run infinitas
		 * @var <type>
		 */
		private $__requiredExtensions = array(
			'zlib' => 'Zlib is required for some of the functionality in Infinitas'
		);

		/**
		 * locations of sql files
		 * @var <type>
		 */
		public $sql = array();

		public $config = array();

		private $__licence = array();

		private $__welcome = array();

		public $steps = array(
			'welcome',
			'database',
			'install',
			'admin_user',
		);

		public function __construct(){
			$message = array(
				__d('installer',
					'Thank-you for choosing %s to power your website.'),
				__d('installer',
					'Since you are on the %s installer you probably know a bit about %s. '. "\n" .
					'Before you go to the next step, make sure that you have create '. "\n" .
					'a database and you have the database details at hand. '. "\n" .
					'If you are unsure how to create a database, contact your web '. "\n" .
					'host support.'),
				__d('installer',
					'%s uses the MIT License, the full license is shown below for '. "\n" .
					'your information. Unless you plan on modifiying and '. "\n" .
					'redistributing %s you do not need to worry about the license. '. "\n" .
					'Note that this license only applies to the %s core code, some '. "\n" .
					'extensions may have other licenses.')
			);



			$siteName = '<b>Infinitas</b>';

			$this->__welcome['html'] =  str_replace('%s', $siteName, implode('</p><p>', $message));
			$this->__welcome['text'] = strip_tags(str_replace('</p><p>', "\r\n", $this->__welcome['html']));

			$date = date('Y');
			$this->__license['html'] = <<<LICENCE
<blockquote><h2>MIT License</h2><p>Copyright &copy; 2009 - $date Infinitas</p><p>Permission is hereby granted, free of charge,
to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without
restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:</p><p>The
above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</p><p>THE
SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE
FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p></blockquote>
LICENCE;
			$text = explode('</p><p>', str_replace(array("\n", '&copy;'), array('', '©'), $this->__license['html']));

			$this->__license['text'] = '';
			foreach($text as $t){
				$this->__license['text'] .= wordwrap(strip_tags($t), 75, "\r\n");
				$this->__license['text'] .= "\r\n";
			}

			$this->__paths = array(
				'Config/database.php' => array(
					'type' => 'write',
					'message' => 'The database configuration (%s) file should be writable during the ' .
						'installation process. It should be set to be read-only once Infinitas has been installed.'
				),
				'tmp' => array(
					'type' => 'write',
					'message' => 'The temporary directory (%s) should be writable by Infinitas. Caching ' .
						' will not work otherwise and Infinitas will perform very poorly.',
				)
			);

			App::import('lib', 'ClearCache.ClearCache');
			$ClearCache = new ClearCache();

			$ClearCache->run();
		}

		/**
		 * get the licence to display on the installler.
		 *
		 * @param string $type the type of licence to return
		 *
		 * @return string the licence
		 */
		public function getLicense($type = 'html'){
			return $this->__getData($type, '__license');
		}

		/**
		 * get the welcome message to display on the installler.
		 *
		 * @param string $type the type of licence to return
		 *
		 * @return string the licence
		 */
		public function getWelcome($type = 'html'){
			return $this->__getData($type, '__welcome');
		}

		/**
		 * @brief check if the server has correct support for infinitas
		 *
		 * @access public
		 *
		 * @return array
		 */
		public function checkCore() {
			$core = array();

			if(phpversion() < $this->__phpVersion) {
				$core[] = sprintf(__('PHP version %s detected, %s needs at least version %s.'), phpversion(), 'Infinitas', $this->__phpVersion . '.x');
			}

			foreach($this->__requiredExtensions as $extension => $message) {
				if(!extension_loaded($extension)) {
					$core[] = __($message);
				}
			}

			return $core;
		}

		/**
		 * @brief check the paths have the correct permissions
		 *
		 * @access public
		 *
		 * @return array
		 */
		public function checkPaths() {
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
					$paths[] = sprintf(__($options['message']), APP.$path);
				}
			}

			return $paths;
		}

		/**
		 * @brief check the settings of php
		 *
		 * Check that everything is configured properly for infinitas to run
		 *
		 * @access public
		 *
		 * @return type
		 */
		public function checkIniSettings() {
			$recomendations = array();

			foreach($this->__recommendedIniSettings as $k => $setting) {
				if((int)ini_get($setting['setting']) !== $setting['recomendation']) {
					$setting['current'] = (int)ini_get($setting['setting']);
					$setting['desc'] = __($setting['desc']);
					$recomendations[] = $setting;
				}
			}

			return $recomendations;
		}

		private function __getData($type, $from){
			$type = (string)$type;

			if(!isset($this->{$from}[$type])){
				$type = 'html';
			}

			return ($type == 'text') ? html_entity_decode($this->{$from}[$type]) : $this->{$from}[$type];
		}

		/**
		 * check what db's are available
		 * @return array
		 */
		public function getSupportedDbs($databaseSupported = false) {
			foreach($this->__supportedDatabases as $cakeType => $supportedDatabase) {
				if(function_exists($supportedDatabase['function'])) {
					$this->__supportedDatabases[$cakeType]['has'] = sprintf(
						__('%s (Version %s or newer)'), $supportedDatabase['name'], $supportedDatabase['version']
					);
				}
			}

			return ($databaseSupported == true) ? !empty($this->__supportedDatabases) : $this->__supportedDatabases;
		}

		/**
		 * @brief check if connection details are valid
		 *
		 * @access private
		 *
		 * @param array $connection the database config to check
		 *
		 * @return bool true if valid, else false
		 */
		private function __validDbConfig($connection, $return = false) {
			App::import('Model', 'Installer.Install');
			$Install = new Install(false, false, false);
			$Install->set($connection);

			if(!$Install->validates()) {
				return false;
			}

			if($return) {
				return $Install->data;
			}

			return true;
		}

		/**
		 * Test if the details provided to connect to the database are correct
		 */
		public function testConnection($connection = array()){
			$connection = $this->__validDbConfig($connection, true);
			if($connection) {
				$adminConnectionDetails = (!isset($connection['Admin'])) ? false : array_merge($connection['Install'], $connection['Admin']);

				$connection['Install']['datasource'] = 'Database/' . $connection['Install']['driver'];
				$InstallerConnection = ConnectionManager::create('installer', $connection['Install']);
				if(!is_callable(array($InstallerConnection, 'isConnected')) || !$InstallerConnection->isConnected()) {
					return false;
				}

				if(isset($connection['Admin']['login']) && trim($connection['Admin']['login']) != '') {
					$InstallerRootConnection = ConnectionManager::create('admin', $adminConnectionDetails);
					if(!is_callable(array($InstallerRootConnection, 'isConnected')) || !$InstallerRootConnection->isConnected()) {
						return false;
					}
				}

				$version = $this->__databaseVersion($connection['Install']);
				if(version_compare($version, $this->__supportedDatabases[$connection['Install']['driver']]['version']) >= 0) {
					return true;
				}

				return array(
					'versionError' => $version,
					'requiredDb' => $this->__supportedDatabases[$connection['driver']]['version']
				);
			}

			return false;
		}

		public function installPlugins($dbConfig) {
			$dbConfig = $this->__validDbConfig($dbConfig, true);
			$dbConfig['Install']['datasource'] = 'Database/' . $dbConfig['Install']['driver'];
			$db = ConnectionManager::create('default', $dbConfig['Install']);

			$plugins = CakePlugin::loaded();
			natsort($plugins);

			App::uses('ReleaseVersion', 'Installer.Lib');
			$Version = new ReleaseVersion();

			$result['app'] = $this->installPlugin($Version, $dbConfig, 'app');

			//Then install all other plugins
			foreach($plugins as $plugin) {
				$result[$plugin] = $this->installPlugin($Version, $dbConfig, $plugin);
			}

			$this->Plugin = ClassRegistry::init('Installer.Plugin');
			foreach($plugins as $pluginName) {
				$this->Plugin->installPlugin($pluginName, array('sampleData' => false, 'installRelease' => false));
			}

			return $result;
		}

		public function installPlugin($Version, $dbConfig, $plugin = 'app') {
			$configPath = APP . 'Config' . DS;
			$checkFile = $configPath . 'releases' . DS . 'map.php';

			if($plugin !== 'app') {
				$pluginPath = App::pluginPath($plugin);
				$configPath = $pluginPath . 'Config' . DS;
				$checkFile = $configPath . 'config.json';
			}

			$versionResult = false;
			if(file_exists($checkFile) && file_exists($configPath . 'releases' . DS . 'map.php')) {
				try {
					$this->config = array_merge(array('sample_data' => false), $this->config);
					$latest = array_pop($Version->getMapping($plugin));
					$versionResult = $Version->run(
						array(
							'type' => $plugin,
							'version' => $latest['version'],
							'basePrefix' => (isset($dbConfig['prefix']) ? $dbConfig['prefix'] : ''),
							'sample' => (bool)$this->config['sample_data']
						)
					);
				}

				catch (Exception $e) {
					throw $e;
					return false;
				}
			}

			else if(file_exists($checkFile)) {
				// databaseless plugin, but it has a config file
				return true;
			}

			return $versionResult;
		}

		/**
		 * @brief write the database.php file
		 *
		 * take the connection details that were passed in and write the file to disk
		 *
		 * @access public
		 *
		 * @return type
		 */
		public function writeDbConfig($dbConfig = array()) {
			copy(App::pluginPath('Installer') . 'Config' . DS . 'database.install', APP . 'Config' . DS . 'database.php');

			$File = new File(APP . 'Config' . DS . 'database.php', true);
			$content = $File->read();

			$find = array(
				'{default_datasource}',
				'{default_host}',
				'{default_login}',
				'{default_password}',
				'{default_database}',
				'{default_prefix}',
				'{default_port}',
				"''"
			);

			$replacements = array(
				'Database/' . $dbConfig['Install']['driver'],
				$dbConfig['Install']['host'],
				$dbConfig['Install']['login'],
				$dbConfig['Install']['password'],
				$dbConfig['Install']['database'],
				$dbConfig['Install']['prefix'],
				$dbConfig['Install']['port'],
				'null'
			);

			$content = str_replace($find, $replacements, $content);

			if ($File->write($content)) {
				return true;
			}

			return false;
		}

		/**
		 * remove unused details from the supplied connection and set the root
		 * username / password if there is one provided.
		 */
		public function cleanConnectionDetails($connectionDetails = array()) {
			$config = $connectionDetails['connection'];
			unset($config['step']);

			if(empty($config['port']) || trim($config['port']) == '') {
				unset($config['port']);
			}

			if(empty($config['prefix']) || trim($config['prefix']) == '') {
				unset($config['prefix']);
			}

			$connectionDetails['root'] = array_merge(
				array('login' => false, 'password' => false),
				isset($connectionDetails['root']) ? (array)$connectionDetails['root'] : array()
			);

			if($connectionDetails['root']['login'] && $connectionDetails['root']['password']) {
				$config['login'] = $connectionDetails['root']['login'];
				$config['password'] = $connectionDetails['root']['password'];
			}

			return $config;
		}

		/**
		 * get the version of the currently selected database engine.
		 *
		 * @param <type> $connectionDetails
		 * @return <type>
		 */
		public function __databaseVersion($connectionDetails){
			$requiredVersion = $this->__supportedDatabases[$connectionDetails['driver']];
			$version = ConnectionManager::getDataSource('installer')->query($requiredVersion['versionQuery']);
			$version = isset($version[0][0]['version()']) ? $version[0][0]['version()'] : false;

			$version = explode('-', $version);
			return $version[0];
		}
	}