<?php
/**
 * ClearCache library class
 *
 * PHP versions 4 and 5
 *
 * Copyright 2010, Marc Ypes, The Netherlands
 *
 *
 *
 *
 * @package Infinitas.Data.Lib
 * @copyright     2010 Marc Ypes, The Netherlands
 * @author        Ceeram
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Helps clear content of CACHE subfolders as well as content in cache engines
 * 
 * @package Infinitas.Data.Lib
 */
class ClearCache {

/**
 * Clears content of CACHE subfolders and configured cache engines
 *
 * @return array
 * @access public
 */
	public static function run() {
		$files = self::files();
		$engines = self::engines();
		$assets = self::assets();

		return compact('files', 'engines', 'assets');
	}

/**
 * get cache status
 *
 * @return array
 */
	public static function status() {
		$return = array(
			'files' => array(
				'used' => self::_fileSize(glob(CACHE . '{.,*}' . DS . '*', GLOB_BRACE)),
				'total' => disk_total_space('/'),
				'available' => disk_free_space('/')
			),
			'assets' => array(
				'used' => self::_fileSize(glob(APP . 'webroot' . DS . '*' . DS . 'combined.*')),
				'total' => disk_total_space('/'),
				'available' => disk_free_space('/')
			)
		);

		$keys = Cache::configured();
		foreach ($keys as $key) {
			$config = Cache::settings($key);
			switch($config['engine']) {
				case 'Apc':
						$smaInfo = apc_sma_info(true);
						$cacheInfo = apc_cache_info();
						$return[$config['engine']] = array(
							'used' => $smaInfo['avail_mem'] - $cacheInfo['mem_size'],
							'total' => $cacheInfo['mem_size'],
							'available' => $smaInfo['avail_mem']
						);
						if($return[$config['engine']]['used'] < 0) {
							$return[$config['engine']]['used'] *= -1;
						}
					break;
			}
		}

		return $return;
	}

/**
 * Clears content of CACHE subfolders
 *
 * @param mixed any amount of strings - names of CACHE subfolders or '.' (dot) for CACHE folder itself
 * @return array
 * @access public
 */
	public static function files() {
		$folders = func_get_args();
		if (empty($folders)) {
			$folders = array('.', '*');
		}

		if (count($folders) > 1) {
			$files = glob(CACHE . '{' . implode(',', $folders) . '}' . DS . '*', GLOB_BRACE);
		} else {
			$files = glob(CACHE . current($folders) . DS . '*');
		}

		return self::_deleteFiles($files);
	}

/**
 * clear out combined asset files
 *
 * @return array
 */
	public static function assets() {
		$deleted = $error = array();

		return self::_deleteFiles(glob(APP . 'webroot' . DS . '*' . DS . 'combined.*'));
	}

/**
 * Clears content of cache engines
 *
 * @param mixed any amount of strings - keys of configure cache engines
 * @return array
 * @access public
 */
	public static function engines() {
		$result = array();

		$keys = Cache::configured();

		if ($engines = func_get_args()) {
			$keys = array_intersect($keys, $engines);
		}

		foreach ($keys as $key) {
			$result[$key] = Cache::clear(false, $key);
		}

		return $result;
	}

/**
 * delete files
 *
 * @param array $files
 *
 * @return array
 */
	protected static function _deleteFiles(array $files) {
		$deleted = $error = array();

		foreach ($files as $file) {
			if (is_file($file) && basename($file) !== 'empty') {
				if (unlink($file)) {
					$deleted[] = $file;
				} else {
					$error[] = $file;
				}
			}
		}

		return compact('deleted', 'error');
	}

/**
 * compute the size of the files passed in
 *
 * @param array $files the files to check
 */
	protected static function _fileSize(array $files) {
		$size = 0;
		array_walk($files, function($file) use(&$size) {
			$size += filesize($file);
		});

		return $size;
	}
}