<?php
/**
 * MigrationInformation
 *
 * @package Infinitas.Migration.Lib
 */

/**
 * MigrationInformation
 *
 * @package Infinitas.Migration.Lib
 */
class MigrationInformation {
/**
 * get migrations / installed count for a specific plugin
 *
 * @param string $plugin the name of the plugin.
 *
 * @return array
 */
	public static function status($plugin) {
		if (!InfinitasPlugin::isPlugin($plugin)) {
			return array();
		}
		$return = array(
			'migrations_available' => self::count($plugin),
			'migrations_installed' => self::version($plugin),
			'installed' => InfinitasPlugin::isInstalled($plugin)
		);
		$return['migrations_behind'] = $return['migrations_available'] - $return['migrations_installed'];

		return $return;
	}

/**
* get the current installed version of the migration
*
* @param string $plugin the name of the plugin to check
*
* @return string|boolean
*/
	public static function version($plugin) {
		if (!InfinitasPlugin::isPlugin($plugin)) {
			return null;
		}

		$migration = ClassRegistry::init('SchemaMigration')->find('first', array(
			'conditions' => array(
				'SchemaMigration.type' => $plugin
			),
			'order' => array(
				'SchemaMigration.version' => 'desc'
			)
		));
		if (!isset($migration['SchemaMigration']['version'])) {
			return false;
		}

		return $migration['SchemaMigration']['version'];
	}

/**
 * get the current version for the passed in plugin
 *
 * @param string $plugin the name of the plugin
 *
 * @return integer
 */
	public static function count($plugin) {
		if (!InfinitasPlugin::isPlugin($plugin)) {
			return 0;
		}

		$path = InfinitasPlugin::path($plugin) . 'Config' . DS . 'releases';
		$Folder = new Folder($path);
		$Folder = $Folder->read();

		$Folder = array_flip($Folder[1]);
		unset($Folder['map.php']);

		return count($Folder);
	}
}