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
		 * @brief load up the installer lib
		 * 
		 * The installer uses a lib that is shared between the frontend and shell
		 * installer.
		 * 
		 * @access public
		 */
		public function __construct() {
			parent::__construct();
			
			App::import('lib', 'Installer.Installer');
			$this->InstallerLib = new InstallerLib();
		}

		/**
		 * beforeFilter
		 *
		 * @return void
		 */
		public function beforeFilter() {
			parent::beforeFilter();

			$this->layout = 'installer';
			$this->view = 'View';
			$this->helpers[] = 'Html';

			$this->Wizard->wizardAction = 'index';
			$this->Wizard->steps = array(
				'welcome',
				'database',
				'install',
				'admin_user',
			);

			$this->installerProgress = array(
				'welcome' => __('Welcome'),
				'database' => __('Database configuration'),
				'install' => __('Ready to install'),
				'admin_user' => __('Site administrator'),
			);

			$this->Wizard->completeUrl = array('plugin' => 'installer', 'controller' => 'install', 'action' => 'finish');

			$this->set('installerProgress', $this->installerProgress);
		}

		public function index($step = null) {
			if(filesize(APP.'config'.DS.'database.php') > 0 && $this->Session->read('installing') == false) {
				$this->notice(
					__('Infinitas has already been installed'),
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
		 * @brief welcome page
		 * 
		 * Does a few system checks to make sure that the server is setup to run 
		 * Infinitas. Check things like paths, extentions and version are correct.
		 * 
		 * @access public
		 */
		public function _prepareWelcome() {
			$core = $this->InstallerLib->checkCore();
			$paths = $this->InstallerLib->checkPaths();
			$database = $this->InstallerLib->getSupportedDbs(true);
			$recomendations = $this->InstallerLib->checkIniSettings();

			$this->set(compact('core', 'database', 'paths', 'recomendations'));
			$this->set('supportedDb', $this->__supportedDatabases);
		}

		/**
		 * @brief process welcome
		 * 
		 * There is nothing to do at this stage
		 */
		public function _processWelcome() {
			return true;
		}

		/**
		 * @brief get database connection details
		 * 
		 * Get the supported databases that are installed on the server and show
		 * a form to enter connection details.
		 * 
		 * @access public
		 */
		public function _prepareDatabase() {
			$this->loadModel('Installer.Install');
			
			$database = array();
			foreach($this->InstallerLib->getSupportedDbs() as $databaseType => $config){
				if(isset($config['has'])) {
					$database[$databaseType] = $config['has'];
				}
			}

			$this->set(compact('database'));
		}

		/**
		 * @brief process the details for the databse
		 * 
		 * Check that the details passed in were correct and that the installer 
		 * is able to connect to the database.
		 * 
		 * @access public
		 * 
		 * @return type 
		 */
		public function _processDatabase() {
			$valid = $this->InstallerLib->testConnection($this->data);
			if($valid === true) {
				$this->Session->write('Install.database_config', $this->data);
				return true;
			}
			
			if(is_array($valid)) {
				pr($valid);
				exit;
			}

			return false;
		}

		/**
		 * @brief prepare to install
		 * 
		 * Get a list of plugins that will be installed and ask the user if they
		 * would like to install some sample data.
		 * 
		 * @access public
		 */
		public function _prepareInstall() {
			$_plugins = App::objects('plugin');
			natsort($_plugins);
			
			$plugins = array();
			foreach($_plugins as $_plugin) {
				$configFile = App::pluginPath($_plugin) . 'config' . DS . 'config.json';
				if(file_exists($configFile)) {
					$plugins[$_plugin] = Set::reverse(json_decode(file_get_contents($configFile)));
				}
			}
			
			$this->set('plugins', $plugins);
		}

		/**
		 * @brief install Infinitas and plugins
		 * 
		 * @todo in prepare make checkboxes next to plugin list and allow users to select plugins to install
		 * 
		 * @return type 
		 */
		public function _processInstall() {
			if($this->InstallerLib->installPlugins($this->Session->read('Install.database_config'))) {
				if($this->InstallerLib->writeDbConfig($this->Session->read('Install.database_config'))) {
					return true;
				}
			}
			
			return false;
		}

		/**
		 * @brief get the details of the administrator
		 * 
		 * @access public
		 */
		public function _prepareAdminUser() {
			if(!is_readable(APP . 'config' . DS . 'database.php') || filesize(APP . 'config' . DS . 'database.php') == 0) {
				$this->Session->delete('Wizard');
				$this->notice(
					__('There was an unrecoverable error configuring your database. '.
							'Unfortunately you need to restart the installation process. '.
							'You may need to clear any tables that have been created', true),
					array(
						'level' => 'error',
						'redirect' => '/'
					)
				);
			}

			$this->loadModel('Management.User');

			$this->set('hidePrevious', true);
		}
		
		/**
		 * @brief save the administrator details
		 * 
		 * @access public
		 */
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
	}