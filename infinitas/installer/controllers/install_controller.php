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

class InstallController extends InstallerAppController {
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
	var $components = null;
	var $helpers = null;

	var $phpVersion = '5.0';

	var $sqlVersion = '4';

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

		//App::import('Component', 'Session');
		//$this->Session = new SessionComponent;
	
		$this->sql = array(
			'core_data' => APP . 'infinitas' . DS . 'installer' . DS . 'config' . DS . 'schema' . DS . 'infinitas_core_data.sql',
			'core_sample_data' => APP . 'infinitas' . DS . 'installer' . DS . 'config' . DS . 'schema' . DS . 'infinitas_sample_data.sql',
		);
	}

	/**
	* index
	*
	* @return void
	*/
	function index() {
		$this->set('title_for_layout', __('Installation: Welcome', true));
		// core setup
		$setup = array(
			array(
				'label' => __('PHP version', true) . ' >= ' . $this->phpVersion . '.x',
				'value' => phpversion() >= $this->phpVersion ? 'Yes' : 'No',
				'desc' => 'Php ' . $this->phpVersion . '.x is recomended, although php 4.x may run Infinitas fine.'
			),
			array (
				'label' => __('zlib compression support', true),
				'value' => extension_loaded('zlib') ? 'Yes' : 'No',
				'desc' => 'zlib is required for some of the functionality in Infinitas'
			),
			array (
				'label' => __('MySQL support', true),
				'value' => (function_exists('mysql_connect')) ? 'Yes' : 'No',
				'desc' => 'Infinitas uses mysql for generating dynamic content. Other databases will follow soon.'
			),
			array (
				'label' => __('MySQL Version', true). ' >= ' . $this->sqlVersion . '.x',
				'value' => (substr(str_replace('mysqlnd ', '', mysql_get_client_info()), 0, 1) >= 4) ? 'Yes' : 'No',
				'desc' => 'Infinitas requires Mysql version >= '. $this->sqlVersion
			)
		);

		// path status
		$paths = array(
			array(
				'path' => APP . 'config',
				'writeable' => is_writable(APP . 'config') ? 'Yes' : 'No',
				'desc' => 'This path needs to be writeable for Infinitas to complete the installation.'
			),
			array (
				'path' => APP . 'tmp',
				'writeable' => is_writable(APP . 'tmp') ? 'Yes' : 'No',
				'desc' => 'The tmp dir needs to be writable for caching to work in Infinitas.'
			),
			array (
				'path' => APP . 'webroot',
				'writeable' => is_writable(APP . 'webroot') ? 'Yes' : 'No',
				'desc' => 'This needs to be web accesible or your images and css will not be found.'
			)
		);

		// recomendations
		$recomendations = array (
			array (
				'setting' => 'safe_mode',
				'recomendation' => 'Off',
				'desc' => 'This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0'
				),
			array (
				'setting' => 'display_errors',
				'recomendation' => 'On',
				'desc' => 'Infinitas will handle errors througout the app'
				),
			array (
				'setting' => 'file_uploads',
				'recomendation' => 'On',
				'desc' => 'File uploads are needed for the wysiwyg editors and system installers'
				),
			array (
				'setting' => 'magic_quotes_runtime',
				'recomendation' => 'Off',
				'desc' => 'This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0. Relying on this feature is highly discouraged.'
				),
			array (
				'setting' => 'register_globals',
				'recomendation' => 'Off',
				'desc' => 'This feature has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0. Relying on this feature is highly discouraged.'
				),
			array (
				'setting' => 'output_buffering',
				'recomendation' => 'Off',
				'desc' => 'Infinitas will handle output_buffering for you throughout the app'
				),
			array (
				'setting' => 'session.auto_start',
				'recomendation' => 'Off',
				'desc' => 'Sessions are completly hanled by Infinitas'
				),
			);

		foreach($recomendations as $k => $setting) {
			$recomendations[$k]['actual'] = ((int)ini_get($setting['setting']) ? 'On' : 'Off');
			$recomendations[$k]['state'] = ($recomendations[$k]['actual'] == $setting['setting']) ? 'No' : 'Yes';
		}

		$this->set(compact('setup', 'paths', 'recomendations'));
	}

	function licence() {
		$this->set('title_for_layout', __('Licence', true));
	}

	function __testConnection() {
		return
		mysql_connect($this->data['Install']['host'],
			$this->data['Install']['login'],
			$this->data['Install']['password']
			) &&

		mysql_select_db($this->data['Install']['database']
			);
	}

	/**
	* database setup
	*
	* @return void
	*/
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

	/**
	* configuration
	*
	* @return void
	*/
	function install() {
		$this->set('title_for_layout', __('Install Database', true));
		
		App::import('Core', 'File');
		App::import('Model', 'ConnectionManager');
		
		$db = ConnectionManager::getDataSource('default');

		if (!$db->isConnected()) {
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
				$good = $good && ClassRegistry::init('Management.Config')->save($_config);
			}

			if ($good === true) {
				$this->redirect(array('action' => 'done'));
			}
		}

		ClassRegistry::flush();

		$configs = ClassRegistry::init('Management.Config')->getInstallSetupConfigs();
		$this->set('configs', $configs);
	}

	/**
	* done
	*
	* Remind the user to delete 'install' plugin.
	*
	* @return void
	*/
	function done() {
		$this->pageTitle = __('Installation completed successfully', true);

		if (isset($this->params['named']['rename'])) {
			if (is_dir(APP . 'infinitas' . DS . 'installer') && rename(APP . 'infinitas' . DS . 'installer', APP . 'infinitas' . DS . 'installer' . time())) {
				$this->Session->setFlash(__('The instilation folder has been renamed, if you ever need to run installation again just rename it back to installer.', true));
			}else {
				$this->Session->setFlash(__('Could not find the installer directory.', true));
			}
		}
	}

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
?>