<?php
/**
 * Plugin
 *
 * @package Infinitas.Installer.Model
 */

/**
 * Plugin
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Installer.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class Plugin extends InstallerAppModel {
/**
 * Custom table
 *
 * @var array
 */
	public $useTable = 'plugins';

/**
 * Error list
 *
 * @var array
 */
	public $installError = array();

/**
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'required' => true,
					'message' => __d('installer', 'All plugins are required to have a valid unique identifier based on the RFC4122 definition.')
				),
			),
			'internal_name' => array(
				'unique' => array(
					'rule' => 'isUnique',
					'message' => __d('installer', 'You already have a plugin with this name installed')
				)
			)
		);
	}

/**
 * get a list of all the plugins that are in the system
 *
 * This will not check plugins that are installed, but anything within
 * any of the defined plugin directories.
 *
 * @param string $type list / count
 *
 * @return array|integer
 */
	public function getAllPlugins($type = 'list') {
		$plugins = App::objects('plugin');
		natsort($plugins);

		if($type == 'count') {
			return count($plugins);
		}

		return $plugins;
	}

/**
 * get a list of plugins that have been installed on Infinitas
 *
 * @param string $type list / count / all
 *
 * @return array|integer
 */
	public function getInstalledPlugins($type = 'list') {
		if(!in_array($type, array('list', 'count', 'all'))) {
			$type = 'list';
		}

		$fields = array(
			$this->alias . '.id',
			$this->alias . '.internal_name'
		);

		if($type !== 'list') {
			$fields = array();
		}

		return $this->find(
			$type,
			array(
				'fields' => $fields
			)
		);
	}

/**
 * Get a list of active installed plugins
 *
 * This is used in things like the EventCore to know where to trigger
 * events
 *
 * @param string $type the type of find to do
 *
 * @return array|integer
 */
	public function getActiveInstalledPlugins($type = 'list') {
		return $this->_installedPluginsByState(1, $type);
	}

/**
 * Get a list of installed plugins that are not active
 *
 * @param type $type
 *
 * @return array|integer
 */
	public function getInactiveInstalledPlugins($type = 'list') {
		return $this->_installedPluginsByState(0, $type);
	}

/**
 * DRY code to find plugins by state.
 *
 * Inactive plugins are non-code disabled plugins
 * Active plugins are all core and any other installed + enabled pluings
 *
 * @param bool $active active or inactive
 * @param string $type the find type to return
 *
 * @return array|integer
 */
	protected function _installedPluginsByState($active = 1, $type = 'list') {
		if(!in_array($type, array('list', 'count', 'all'))) {
			$type = 'list';
		}
		$active = (bool)$active;

		$fields = array(
			$this->alias . '.id',
			$this->alias . '.internal_name'
		);

		if($type !== 'list') {
			$fields = array();
		}

		$conditionType = 'or';
		if(!$active) {
			$conditionType = 'and';
		}

		$conditions[$conditionType] = array(
			$this->alias . '.active' => $active,
			$this->alias . '.core' => $active
		);

		return $this->find(
			$type,
			array(
				'fields' => $fields,
				'conditions' => $conditions
			)
		);
	}

/**
 * method to find a list of plugins not yet installed
 *
 * This uses the getAllPlugins() method and getInstalledPlugins() method
 * with array_diff to show a list of plugins that have not yet been installed
 *
 * @param string $type list / count
 *
 * @return array
 */
	public function getNonInstalledPlugins($type = 'list') {
		$nonInstalled = array_diff($this->getAllPlugins(), array_values($this->getInstalledPlugins()));
		natsort($nonInstalled);

		if($type == 'count') {
			return count($nonInstalled);
		}

		return $nonInstalled;
	}

/**
 * install a plugin
 *
 * This method is used to install a plugin and record the details in the
 * plugin table. There are options that can be used for installing your
 * own created plugin or a plugin you have downloaded.
 *
 * $options can be the following
 *  - sampleData true/false to install any sample data
 *  - installRelease true/fase (true will run the fixtures, false will only save the details so it is officially installed)
 *
 * @param string $pluginName the name of the plugin being installed
 * @param array $options the options for the install
 *
 * @return boolean
 */
	public function installPlugin($pluginName, $options = array()) {
		$options = array_merge(array(
			'sampleData' => false,
			'installRelease' => true
		), $options);

		$pluginDetails = InfinitasPlugin::config($pluginName);
		$pluginDetails['dependancies'] = json_encode($pluginDetails['dependancies']);
		$pluginDetails['internal_name'] = $pluginName;
		$pluginDetails['active'] = true;
		$pluginDetails['core'] = strpos(CakePlugin::path($pluginName), APP . 'Core' . DS) !== false;

		$pluginDetails['license'] = !empty($pluginDetails['license']) ? $pluginDetails['license'] : $pluginDetails['author'] . ' (c)';

		$this->create();
		if($this->save(array($this->alias => $pluginDetails))) {
			return InfinitasPlugin::install($pluginName, array(
				'sampleData' => $options['sampleData'],
				'installRelease' => $options['installRelease']
			));
		}

		return false;
	}

/**
 * check if a plugin is installed
 *
 * @param string $plugin the name of the plugin
 *
 * @return boolean
 */
	public function isInstalled($plugin) {
		return (bool)$this->find('count', array(
			'conditions' => array(
				$this->alias . '.internal_name' => $plugin
			)
		));
	}

/**
 * Process install options
 *
 * @param array $data the install data
 *
 * @return boolean
 *
 * @throws CakeException
 */
	public function processInstall($data) {
		if(empty($data['Plugin']['file']['name'])) {
			unset($data['Plugin']['file']);
		}

		if(empty($data['Theme']['file']['name'])) {
			unset($data['Theme']['file']);
		}
		$data = array_filter(Set::flatten($data));

		if(!$data) {
			throw new CakeException(__d('installer', 'Nothing selected to install'));
		}

		switch(current(array_keys($data))) {
			case 'Theme.local':
				return InstallerLib::localTheme(current($data));
				break;
		}

		throw new CakeException(__d('installer', 'Could not complete installation'));
	}

}