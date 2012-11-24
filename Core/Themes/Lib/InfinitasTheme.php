<?php
/**
 * InfinitasTheme
 *
 * @package Infinitas.Theme.Lib
 */

App::uses('InstallerLib', 'Installer.Lib');
App::uses('FolderSymlink', 'Filemanager.Utility');

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
 * Make sure the default theme is available for installing Infinitas
 *
 * @param string $theme the name of the default theme (usually `infinitas`)
 *
 * @return boolean
 *
 * @throws CakeException
 */
	public function defaultThemeInstall($theme = 'infinitas') {
		if (!self::install($theme)) {
			throw new CakeException('Could not configure the default theme, make sure to clone / download it');
		}
		return true;
	}

/**
 * Install a theme
 *
 * Create the required symlinks for a theme to work
 *
 * @param string $theme the name of the theme (its folder)
 *
 * @return void
 *
 * @throws CakeException
 */
	public static function install($theme) {
		$FolderSymlink = new FolderSymlink();
		$themePath = self::themePath($theme) . DS . 'webroot';
		if (!is_dir($themePath)) {
			return true;
		}
		$linkPath = self::linkPath($theme);
		if (!$FolderSymlink->create($linkPath , $themePath)) {
			throw new CakeException('Unable to symlink theme');
		}
		return $FolderSymlink->isLink($linkPath);
	}

/**
 * Uninstall a theme
 *
 * Remove any created symlinks
 */
	public static function uninstall($theme) {
		$FolderSymlink = new FolderSymlink();

		$linkPath = self::linkPath($theme);
		$FolderSymlink->delete($linkPath);

		return !$FolderSymlink->isLink($linkPath);
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
				return ClassRegistry::init('Themes.Theme')->find('installed');

			case 'notInstalled':
				return array_intersect(self::themes('all'), self::themes('installed'));

			default:
			case 'all':
				return self::_getThemes();
		}
	}

/**
 * Find themes within the application
 *
 * Themes should be located in APP/View/Themed. This can be symlinked from another
 * location and will be symilined to the webroot for fast asset loading.
 *
 * @return array
 */
	protected static function _getThemes() {
		$Folder = new Folder(self::themePath());

		$return = array();
		foreach (current($Folder->read()) as $theme) {
			$return[$theme] = Inflector::humanize(Inflector::underscore($theme));
		}

		return $return;
	}

/**
 * Get a count of layouts available in the theme
 *
 * @param string $theme the name of a theme
 *
 * @return integer
 */
	public static function layoutCount($theme) {
		return count(Hash::flatten(self::layouts($theme)));
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
		if ($theme) {
			return self::__layoutsFor($theme);
		}

		$return = array();
		foreach (self::themes() as $theme) {
			$return[$theme] = self::__layoutsFor($theme);
		}

		return $return;
	}

/**
 * Get the screen shots for a theme
 *
 * @param string $theme the name of the theme
 *
 * @return array
 */
	public static function screenshots($theme) {
		$path = self::linkPath($theme) . DS . 'img' . DS . 'screenshots' . DS;
		$Folder = new Folder($path);
		$Folder = end($Folder->read());
		foreach ($Folder as &$screenshot) {
			$screenshot = str_replace(WWW_ROOT, DS, $path) . $screenshot;
		}

		return $Folder;
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
		if (!empty($temp['Theme']['name'])) {
			$theme = $temp['Theme']['name'];
		}

		$Folder = new Folder(self::themePath($theme) . DS . 'Layouts');
		$files = $Folder->read();

		$return = array();
		foreach ($files[1] as &$file) {
			$file = pathinfo($file);

			$type = __d('themes', 'Frontend');
			if (strstr($file['filename'], 'admin') !== false) {
				$type = __d('themes', 'Admin');
			}

			if (strstr($file['filename'], 'installer') !== false) {
				$type = __d('themes', 'Install');
			}

			$return[$type][$file['filename']] = Inflector::humanize(str_replace('admin', '', $file['filename']));
			if (empty($return[$type][$file['filename']])) {
				$return[$type][$file['filename']] = __d('themes', 'Main');
			}
		}

		return $return;
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
	public function config($theme) {
		$path = self::themePath($theme) . DS . 'config.json';
		if (!is_file($path)) {
			throw new ThemesConfigurationException('Missing configuration for selected theme');
		}

		$File = new File($path);
		$File = json_decode($File->read(), true);
		if (empty($File)) {
			throw new ThemesConfigurationException(vsprintf('Configuration for "%s" seems invalid', array(
				$theme
			)));
		}
		return array('Theme' => $File);
	}

/**
 * generate the path to a theme dir
 *
 * If the specific theme is available it will return the path to the
 * theme, if not it will return the path to where the themes are kept
 *
 * @param string $theme the name of the theme
 *
 * @return string
 */
	public static function themePath($theme = null) {
		if (!$theme) {
			return APP . 'View' . DS . 'Themed';
		}

		return self::themePath() . DS . $theme;
	}

/**
 * Get the path to the themes symlink in the webroot
 *
 * If a theme is specified the path will be to the theme, else to the folder
 * where themes are linked to.
 *
 * @param string $theme the name of the theme (folder)
 *
 * @return string
 */
	public function linkPath($theme = null) {
		if (!$theme) {
			return WWW_ROOT . 'theme';
		}

		return self::linkPath() . DS . $theme;
	}

}