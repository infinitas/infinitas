<?php
	/**
	 * Model for themes
	 *
	 * handles the themes for infinitas finding and making sure only one can be
	 * active at a time.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package management
	 * @subpackage management.models.theme
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */
	class Theme extends ThemesAppModel {
		public $useTable = 'themes';
		
		public $hasMay = array(
			'Route' => array(
				'className' => 'Routes.Route'
			)
		);

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
		 * @brief Get the current Theme
		 * 
		 * @access public
		 * 
		 * @return array $theme the current theme.
		 */
		public function getCurrentTheme() {
			$theme = Cache::read('current_theme', 'core');

			if ($theme !== false) {
				return $theme;
			}
			
			try {
				$theme = $this->find(
					'first',
					array(
						'fields' => array(
							$this->alias . '.' . $this->primaryKey,
							$this->alias . '.' . $this->displayField,
							$this->alias . '.default_layout',
							$this->alias . '.core'
						),
						'conditions' => array(
							$this->alias . '.active' => true
						)
					)
				);

				Cache::write('current_theme', $theme, 'core');
			}
			
			catch(Exception $e) {
				CakeLog::write('core', $e->getMessage());
				$theme = array();
			}

			return $theme;
		}

		/**
		 * @brief before saving
		 *
		 * if the new / edited theme is active deactivte everything.
		 * 
		 * @access public
		 * 
		 * @return bool
		 */
		public function beforeSave() {
			if(isset($this->data[$this->alias]['active']) && $this->data[$this->alias]['active']) {
				return $this->deactivateAll();
			}

			return parent::beforeSave();
		}

		/**
		 * @brief before deleteing
		 *
		 * If the theme is active do not let it be deleted.
		 * 
		 * @access public
		 * 
		 * @param $cascade bool
		 * 
		 * @return bool true to delete, false to stop
		 */
		public function beforeDelete($cascade) {
			$active = $this->read('active');
			return isset($active[$this->alias]['active']) && !$active[$this->alias]['active'];
		}

		/**
		 * @brief deactivate all themes.
		 *
		 * This is used before activating a theme to make sure that there is only
		 * ever one theme active.
		 * 
		 * @access public
		 *
		 * @return bool true on sucsess false if not.
		 */
		public function deactivateAll() {
			return $this->updateAll(
				array(
					$this->alias . '.active' => false
				)
			);
		}
		
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
					throw new Exception(__d('themes', 'Could not install the "%s" theme', $theme));
				}
				
				throw new Exception(__d('themes', 'Could not symlink the theme directory'));
			}
			
			throw new Exception(__d('themes', 'Path error installing the "%s" theme', $theme));
		}
		
		private function __parseThemeConfig($path) {
			if(!is_file($path . DS . 'config.json')) {
				throw new Exception('Missing configuration for selected theme');
			}
			
			$File = new File($path . DS . 'config.json');
			return array($this->alias => json_decode($File->read(), true));
		}
		
		/**
		 * @brief get a list of themes that are already installed 
		 * 
		 * @access public
		 * 
		 * @return array list of installed themes
		 */
		public function installed() {
			$themes = $this->find(
				'list',
				array(
					'fields' => array(
						$this->alias . '.' . $this->primaryKey,
						$this->alias . '.' . $this->displayField
					)
				)
			);
			
			foreach($themes as &$theme) {
				$theme = Inflector::humanize($theme);
			}
			
			return $themes;
		}
		
		/**
		 * @brief get a list of themes that are not yet installed
		 * 
		 * @access public
		 * 
		 * @return array list of themes that are not installed
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