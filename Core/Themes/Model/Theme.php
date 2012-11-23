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
 * custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'currentTheme' => true,
		'installed' => true
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
 * Find the currently selected theme
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 *
 * @throws ThemesConfigurationException
 * @throws NoThemeConfiguredException
 */
	protected function _findCurrentTheme($state, $query, $results = array()) {
		if($state == 'before') {
			$query = array_merge(array(
				'admin' => null
			), $query);

			if($query['admin'] === null) {
				throw new NoThemeSelectedException(array());
			}

			$query['fields'] = array_merge((array)$query['fields'], array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.' . $this->displayField,
				$this->alias . '.default_layout'
			));

			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias . '.admin' => $query['admin']
			));

			return $query;
		}

		if(empty($results)) {
			throw new NoThemeConfiguredException(array());
		}

		$results = current($results);
		Cache::write('current_theme', $results, 'core');

		return $results;
	}

/**
 * BeforeSave callback
 *
 * if the new / edited theme is active deactivte everything.
 *
 * @return boolean
 */
	public function beforeSave($options = array()) {
		if(isset($this->data[$this->alias]['admin']) && $this->data[$this->alias]['admin']) {
			return $this->deactivateAll('admin');
		}
		if(isset($this->data[$this->alias]['frontend']) && $this->data[$this->alias]['frontend']) {
			return $this->deactivateAll('frontend');
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
	public function beforeDelete($cascade = true) {
		$state = $this->read(array('admin', 'frontend'));
		if(empty($state) ||  $state[$this->alias]['admin'] || $state[$this->alias]['frontend']) {
			return false;
		}

		return true;
	}

/**
 * deactivate all themes.
 *
 * This is used before activating a theme to make sure that there is only
 * ever one theme active.
 *
 * @return boolean
 */
	public function deactivateAll($type) {
		return $this->updateAll(array(
			$this->alias . '.' . $type => false
		));
	}

/**
 * Install a theme
 *
 * This method will create the symlink for a theme and then attempt to save
 * the data from the config to the db.
 *
 * @param string $theme the name of a theme to install
 *
 * @return boolean
 *
 * @throws CakeException
 */
	public function install($theme) {
		if(InfinitasTheme::install($theme) && $this->save(InfinitasTheme::config($theme))) {
			return true;
		}

		InfinitasTheme::uninstall($theme);
		throw new CakeException(__d('themes', 'Could not install the "%s" theme', $theme));
	}

	protected function _findInstalled($state, $query, $results = array()) {
		if($state == 'before') {
			$query['fields'] = array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.' . $this->displayField
			);
			return $query;
		}

		$results = Hash::combine($results,
			'{n}.' . $this->alias . '.' . $this->primaryKey,
			'{n}.' . $this->alias . '.' . $this->displayField
		);

		$return = array();
		foreach($results as $result) {
			$return[$result] = Inflector::humanize($result);
		}

		return $return;
	}

}