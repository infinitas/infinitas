<?php
/**
 * InfinitasTheme
 *
 * @package Infinitas.Theme.Lib
 */

App::uses('InstallerLib', 'Installer.Lib');

/**
 * InfinitasTheme
 *
 * This is similar to InfinitasPlugin but for managing themes
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Theme.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InfinitasTheme {
/**
 * Install a theme
 */
	public static function install() {

	}

/**
 * Uninstall a theme
 */
	public static function uninstall() {

	}

/**
 * Get the current theme
 */
	public static function current() {

	}

/**
 * Get available themes
 *
 * @param string $type the type of theme installed|notInstalled|all
 *
 * @return array
 */
	public static function themes($type = 'installed') {
		switch($type) {
			case 'installed':
				return ClassRegistry::init('Themes.Theme')->installed();
				break;

			case 'notInstalled':
				return array_intersect(self::themes('all'), self::themes('installed'));
				break;

			default:
			case 'all':
				$return = array();
				foreach(InfinitasPlugin::listPlugins('loaded') as $plugin) {
					foreach(InstallerLib::findThemes($plugin) as $theme) {
						$return[$plugin . '.' . $theme] = Inflector::humanize(Inflector::underscore($plugin . Inflector::camelize($theme)));
					}
				}

				foreach(InstallerLib::findThemes() as $theme) {
					$return[$theme] = Inflector::humanize(Inflector::underscore($theme));
				}
				return $return;
				break;
		}
	}

/**
 * Get layouts for a theme
 *
 * Passing a theme will get only its layouts, or pass null for all themes layouts
 *
 * @param string $theme the theme name
 *
 * @return array
 */
	public static function layouts($theme = null) {
		if($theme) {
			return self::__layoutsFor($theme);
		}

		$return = array();
		foreach(self::themes() as $theme) {
			$return[$theme] = self::__layoutsFor($theme);
		}

		return $return;
	}

/**
 * Get the layouts for a theme
 *
 * @param string $theme the themes name
 *
 * @return array
 */
	private static function __layoutsFor($theme) {
		$temp = ClassRegistry::init('Themes.Theme')->find(
			'first',
			array(
				'fields' => array(
					'Theme.name'
				),
				'conditions' => array(
					'Theme.id' => $theme
				)
			)
		);
		if(!empty($temp['Theme']['name'])) {
			$theme = $temp['Theme']['name'];
		}

		list($plugin, $theme) = pluginSplit($theme);

		if(!$plugin) {
			$plugin = self::findPlugin($theme);
		}

		$Folder = new Folder(self::themePath($plugin, $theme) . DS . 'Layouts');
		$files = $Folder->read();

		$return = array();
		foreach($files[1] as &$file) {
			$file = pathinfo($file);
			$return[$file['filename']] = Inflector::humanize($file['filename']);
		}

		return $return;
	}

/**
 * Find the plugin for a theme
 *
 * @param string $theme the theme name to look up
 *
 * @return string
 *
 * @throws CakeException
 */
	public static function findPlugin($theme) {
		foreach(self::themes('all') as $pluginTheme => $niceName) {
			list($p, $t) = pluginSplit($pluginTheme);
			if($t == $theme) {
				return $p;
			}
		}

		throw new CakeException('Could not find selected theme');
	}

/**
 * generate the path to a plugins theme dir
 *
 * If the specific theme is available it will return the path to the
 * theme, if not it will return the path to where the themes for that plugin
 * are kept
 *
 * If no plugin is null, it is assumed that the path for app themes are required
 *
 * @param string $plugin the name of the plugin
 * @param string $theme the name of the theme
 *
 * @return string
 */
	public static function themePath($plugin = null, $theme = null) {
		if(!$plugin) {
			if(!$theme) {
				return APP . 'View' . DS . 'Themed';
			}

			return APP . 'View' . DS . 'Themed' . DS . $theme;
		}

		if(!$theme) {
			return InfinitasPlugin::path($plugin) . 'View' . DS . 'Themed';
		}

		return InfinitasPlugin::path($plugin) . 'View' . DS . 'Themed' . DS . $theme;
	}

}