<?php
/**
 * GoogleAppHelper
 *
 * @package Infinitas.Google.Helper
 */

/**
 * GoogleAppHelper
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Google.Helper
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class GoogleAppHelper extends AppHelper{
/**
 * Constructor
 *
 * Load options and configure object
 *
 * @param array $options the options
 *
 * @return void
 */
	public function __construct($options = array()) {
		$default = array(
			'cachePath' => dirname(dirname(dirname(__FILE__))) . DS . 'webroot' . DS . 'img' . DS . get_class($this) . DS,
			'cacheImagePath' => sprintf('/google/img/%s/', get_class($this))
		);

		$options = array_merge($default, (array)$options);
		$this->cachePath = $options['cachePath'];
		$this->cacheImagePath = $options['cacheImagePath'];
	}

/**
 * Check if the cache file exists
 *
 * @param array $data the image data
 *
 * @return boolean
 */
	protected function _checkCache($data) {
		$file = sha1(serialize($data)).'.png';
		if (is_file($this->cachePath.$file)) {
			return $this->Html->image($this->cacheImagePath.$file );
		}

		return false;
	}

/**
 * Write a cache file
 *
 * @param array $data the image data
 * @param string $url the url
 *
 * @return void
 */
	protected function _writeCache($data, $url) {
		$file = sha1(serialize($data)).'.png';

		if(is_writable($this->cachePath)) {
			$contents = file_get_contents($url);

			$fp = fopen($this->cachePath.$file, 'w');
			fwrite($fp, $contents);
			fclose($fp);

			if (!is_file($this->cachePath.$file)) {
				$this->__errors[] = __d('google', 'Could not create the cache file');
			}
		}
	}

}