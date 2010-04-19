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

class UpgradeController extends InstallerAppController {
	/**
	* Controller name
	*
	* @var string
	* @access public
	*/
	var $name = 'Upgrade';

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

	/**
	* beforeFilter
	*
	* @return void
	*/
	function beforeFilter() {
		parent::beforeFilter();

		App::import('Component', 'Session');
		$this->Session = new SessionComponent;
		$this->Session->startup($this);

		$this->layout = 'admin';

		$this->helpers[] = 'Html';
		$this->helpers[] = 'Form';
	}

	/**
	* index
	*
	* @return void
	*/
	function admin_index() {
	}

	/**
	* configuration
	*
	* @return void
	*/
	function admin_update() {
		// Can be 'app' or a plugin name
		$type = 'app';

		App::import('Lib', 'Migrations.MigrationVersion');
		// All the job is done by MigrationVersion
		$version = new MigrationVersion();

		// Get the mapping and the latest version avaiable
		$mapping = $version->getMapping($type);
		$latest = end($mapping);

		// Run it to latest version
		if($version->run(array('type' => $type, 'version' => $latest['version'])))
		{
			ClassRegistry::init('Installer.Release')->installData(false);

			$this->Session->setFlash(__('Your database has been upgraded', true));
			$this->redirect('/admin');
		}
		else
		{
			//SessionComponent::setFlash(__('There was an error installing database data.', true));
		}
	}
}
?>