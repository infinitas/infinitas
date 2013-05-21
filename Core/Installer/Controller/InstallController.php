<?php
/**
 * InstallController
 *
 * @package Infinitas.Installer.Controller
 */

Configure::write('Session.save', 'php');

/**
 * InstallController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Installer.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InstallController extends Controller {
/**
 * No models required
 *
 * @var array
 */
	public $uses = false;

/**
 * Components to load
 *
 * @var array
 */
	public $components = array(
		'Libs.Wizard',
		'Session'
	);

/**
 * Helpers to load
 *
 * @var array
 */
	public $helpers = array(
		'Html',
		'Form',
		'Libs.Wizard',
		'Text'
	);

/**
 * Minimum php version
 *
 * @var string
 */
	private $__phpVersion = '5.3';

/**
 * Supported database options
 *
 * @var array
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
 * Required modules
 *
 * @var array
 */
	private $__requiredExtensions = array(
		'zlib' => 'Zlib is required for some of the functionality in Infinitas'
	);

/**
 * Setup checks
 *
 * @var array
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
 * Paths used for the installer
 *
 * @var array
 */
	private $__paths;

/**
 * Installer progress
 *
 * @var array
 */
	public $installerProgress = array();

/**
 * Constructor
 *
 * The installer uses a lib that is shared between the frontend and shell
 * installer.
 *
 * @return void
 */
	public function __construct($request = null, $response = null) {
		parent::__construct($request, $response);

		InfinitasPlugin::loadForInstaller();

		App::uses('InstallerLib', 'Installer.Lib');
		$this->InstallerLib = new InstallerLib();
	}

/**
 * BeforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->layout = 'installer';
		$this->viewClass = 'View';
		$this->helpers[] = 'Html';

		$this->Wizard->wizardAction = 'index';
		$this->Wizard->steps = array(
			'welcome',
			'database',
			'install',
			'admin_user',
		);

		$this->installerProgress = array(
			'welcome' => __d('insatller', 'Welcome'),
			'database' => __d('insatller', 'Database configuration'),
			'install' => __d('insatller', 'Ready to install'),
			'admin_user' => __d('insatller', 'Site administrator'),
		);

		$this->Wizard->completeUrl = array('plugin' => 'installer', 'controller' => 'install', 'action' => 'finish');

		$this->set('installerProgress', $this->installerProgress);
		return true;
	}

/**
 * Trigger event to get the theme that should be used for the installer
 *
 * You can provide your own custom install theme by using the `onInstallerTheme`
 * event from your plugin. You can return any theme but if its not the default
 * will need to call the code for creating a symlink first assets are required.
 *
 * @see InfinitasTheme::install()
 *
 * @return void
 *
 * @throws InvalidArgumentException
 */
	public function beforeRender() {
		$theme = EventCore::trigger($this, 'installerTheme');
		if (count($theme['installerTheme']) > 1) {
			unset($theme['installerTheme']['Themes']);
		}
		$theme = current($theme['installerTheme']);
		if (empty($theme)) {
			throw new InvalidArgumentException(__d('installer', 'No theme handler found'));
		}

		$this->theme = $theme['theme'];
		$this->layout = $theme['layout'];

		parent::beforeRender();
	}

/**
 * Installer index
 *
 * @param string $step
 *
 * @return void
 */
	public function index($step = null) {
		$file = APP . 'Config' . DS . 'database.php';
		if (file_exists($file) && filesize($file) > 0 && $this->Session->read('installing') == false) {
			$this->Session->setFlash(__d('installer', 'Infinitas has already been installed'));
			$this->redirect('/');
		}

		$this->Session->write('installing', true);
		$this->set('title_for_layout', $this->installerProgress[($step == null) ? 'welcome' : $step]);
		$this->Wizard->process($step);
	}

/**
 * Complete the installation
 *
 * @return void
 */
	public function finish() {
		$this->Session->write('installing', false);
		$this->set('title_for_layout', 'Finished');
		$this->set('hidePrevious', true);
		$this->set('hideNext', true);
	}

/**
 * welcome page
 *
 * Does a few system checks to make sure that the server is setup to run
 * Infinitas. Check things like paths, extentions and version are correct.
 *
 * @return void
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
 * process welcome
 *
 * There is nothing to do at this stage
 *
 * @return boolean
 */
	public function _processWelcome() {
		return true;
	}

/**
 * get database connection details
 *
 * Get the supported databases that are installed on the server and show
 * a form to enter connection details.
 *
 * @return void
 */
	public function _prepareDatabase() {
		$this->loadModel('Installer.Install');

		$database = array();
		foreach ($this->InstallerLib->getSupportedDbs() as $databaseType => $config) {
			if (isset($config['has'])) {
				$database[$databaseType] = $config['has'];
			}
		}

		$this->set(compact('database'));
	}

/**
 * process the details for the databse
 *
 * Check that the details passed in were correct and that the installer
 * is able to connect to the database.
 *
 * @return boolean
 */
	public function _processDatabase() {
		$valid = $this->InstallerLib->testConnection($this->request->data);
		if ($valid === true) {
			$this->Session->write('Install.database_config', $this->request->data);
			return true;
		}

		if (is_array($valid)) {
			pr($valid);
			exit;
		}

		return false;
	}

/**
 * prepare to install
 *
 * Get a list of plugins that will be installed and ask the user if they
 * would like to install some sample data.
 *
 * @return void
 */
	public function _prepareInstall() {
		$_plugins = CakePlugin::loaded();
		natsort($_plugins);

		$plugins = array();
		foreach ($_plugins as $_plugin) {
			$configFile = CakePlugin::path($_plugin) . 'Config' . DS . 'config.json';
			if (file_exists($configFile)) {
				$plugins[$_plugin] = Set::reverse(json_decode(file_get_contents($configFile)));
			}
		}

		$this->set('plugins', $plugins);
	}

/**
 * install Infinitas and plugins
 *
 * @todo in prepare make checkboxes next to plugin list and allow users to select plugins to install
 *
 * @return boolean
 */
	public function _processInstall() {
		$dbConfig = $this->Session->read('Install.database_config');

		return $this->InstallerLib->writeDbConfig($dbConfig) && $this->InstallerLib->installPlugins($dbConfig);
	}

/**
 * get the details of the administrator
 *
 * @return void
 */
	public function _prepareAdminUser() {
		if (!is_readable(APP . 'Config' . DS . 'database.php') || filesize(APP . 'Config' . DS . 'database.php') == 0) {
			$this->Session->delete('Wizard');
			$this->notice(
				__d('insatller', 'There was an unrecoverable error configuring your database. '.
						'Unfortunately you need to restart the installation process. '.
						'You may need to clear any tables that have been created', true),
				array(
					'level' => 'error',
					'redirect' => '/'
				)
			);
		}

		$this->loadModel('Users.User');

		$this->set('hidePrevious', true);
	}

/**
 * save the administrator details
 *
 * @return boolean
 */
	public function _processAdminUser() {
		$this->loadModel('Users.User');

		$this->request->data['User']['ip_address'] = env('REMOTE_ADDR');
		$this->request->data['User']['browser'] = env('HTTP_USER_AGENT');
		$this->request->data['User']['operating_system'] = '';
		$this->request->data['User']['country'] = '';
		$this->request->data['User']['city'] = '';
		$this->request->data['User']['group_id'] = 1;
		$this->request->data['User']['active'] = 1;
		$this->request->data['User']['last_login'] = date('Y-m-d H:i:s');

		if ($this->User->save($this->request->data)) {
			return true;
		}

		return false;
	}

}