<?php
/**
 * Theme
 *
 * @package Infinitas.Themes.Model
 */

/**
 * Theme
 *
 * handles the themes for infinitas finding and making sure only one can be
 * active at a time.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Themes.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class Theme extends ThemesAppModel {
/**
 * Custom table
 *
 * @var string
 */
	public $useTable = 'themes';

/**
 * HasMany relations
 *
 * @var array
 */
	public $hasMay = array(
		'Route' => array(
			'className' => 'Routes.Route'
		)
	);

/**
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function  __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('themes', 'Please enter the name of the them')
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('themes', 'There is already a them with this name')
				)
			),
			'author' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('themes', 'Please enter the name of the author')
				)
			),
			'url' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('themes', 'Please enter the url for this theme')
				),
				'url' => array(
					'rule' => array('url', true),
					'message' => __d('themes', 'Please enter a valid url')
				)
			)
		);
	}

/**
 * Get the current Theme
 *
 * @return array
 */
	public function getCurrentTheme() {
		$theme = Cache::read('current_theme', 'core');

		if ($theme !== false) {
			return $theme;
		}

		try {
			$theme = $this->find('first', array(
				'fields' => array(
					$this->alias . '.' . $this->primaryKey,
					$this->alias . '.' . $this->displayField,
					$this->alias . '.default_layout',
					$this->alias . '.core'
				),
				'conditions' => array(
					$this->alias . '.active' => true
				)
			));

			Cache::write('current_theme', $theme, 'core');
		}

		catch(Exception $e) {
			CakeLog::write('core', $e->getMessage());
			$theme = array();
		}

		return $theme;
	}

/**
 * BeforeSave callback
 *
 * if the new / edited theme is active deactivte everything.
 *
 * @return boolean
 */
	public function beforeSave() {
		if(isset($this->data[$this->alias]['active']) && $this->data[$this->alias]['active']) {
			return $this->deactivateAll();
		}

		return parent::beforeSave();
	}

/**
 * BeforeDelete callback
 *
 * If the theme is active do not let it be deleted.
 *
 * @param boolean $cascade
 *
 * @return boolean
 */
	public function beforeDelete($cascade) {
		$active = $this->read('active');
		return isset($active[$this->alias]['active']) && !$active[$this->alias]['active'];
	}

/**
 * deactivate all themes.
 *
 * This is used before activating a theme to make sure that there is only
 * ever one theme active.
 *
 * @return boolean
 */
	public function deactivateAll() {
		return $this->updateAll(array(
			$this->alias . '.active' => false
		));
	}

/**
 * Install a theme
 *
 * @param string $theme the Plugin.Theme name
 *
 * @return boolean
 *
 * @throws CakeException
 */
	public function install($theme) {
		list($plugin, $theme) = pluginSplit($theme);
		$path = InstallerLib::themePath($plugin, $theme);
		$targetSymlink = InstallerLib::themePath(null, $theme);

		if(is_dir($path) && !is_dir($targetSymlink)) {
			if(symlink($path, $targetSymlink)) {
				if($this->save($this->__parseThemeConfig($path))) {
					return true;
				}

				unlink($targetSymlink);
				throw new CakeException(__d('themes', 'Could not install the "%s" theme', $theme));
			}

			throw new CakeException(__d('themes', 'Could not symlink the theme directory'));
		}

		throw new CakeException(__d('themes', 'Path error installing the "%s" theme', $theme));
	}

/**
 * Read the theme config
 *
 * @param string $path the path to the theme
 *
 * @return array
 *
 * @throws CakeException
 */
	private function __parseThemeConfig($path) {
		$path .= DS . 'config.json';
		if(!is_file($path)) {
			throw new CakeException('Missing configuration for selected theme');
		}

		$File = new File($path);
		return array(
			$this->alias => json_decode($File->read(), true)
		);
	}

/**
 * get a list of themes that are already installed
 *
 * @return array
 */
	public function installed() {
		$themes = $this->find('list', array(
			'fields' => array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.' . $this->displayField
			)
		));

		foreach($themes as &$theme) {
			$theme = Inflector::humanize($theme);
		}

		return $themes;
	}

/**
 * get a list of themes that are not yet installed
 *
 * @return array
 */
	public function notInstalled() {
		App::uses('InstallerLib', 'Installer.Lib');
		$installed = $this->installed();

		$notInstalled = array();
		foreach(InfinitasPlugin::listPlugins('loaded') as $plugin) {
			foreach(InstallerLib::findThemes($plugin) as $theme) {
				if(!array_key_exists($theme, $installed)) {
					$notInstalled[$plugin . '.' . $theme] = Inflector::humanize(Inflector::underscore($plugin . Inflector::camelize($theme)));
				}
			}
		}

		foreach(InstallerLib::findThemes() as $theme) {
			if(!linkinfo(InstallerLib::themePath(null, $theme)) && !array_key_exists($theme, $installed)) {
				$notInstalled[$theme] = Inflector::humanize(Inflector::underscore($theme));
			}
		}

		return $notInstalled;
	}

}