<?php
/**
 * ImageHelper
 *
 * @package
 * @author Carl Sutton (dogmatic69)
 * @copyright Copyright (c) 2010
 * @version $Id$
 * @access public
 */
class ImageHelper extends AppHelper {
/**
 * default config for the images
 *
 * @var array
 */
	public $settings = array(
		'width' => '20px'
	);

/**
 * cached list of the places
 *
 * @var array
 */
	protected $_places = array();

/**
 * cached list of the images
 *
 * @var array
 */
	protected $_images = array();

/**
 * get an image
 *
 * @param string $path the relative path (to core images path)
 * @param string $key the key
 *
 * @return boolean|string
 */
	public function image($path, $key, array $config = array()) {
		$image = $this->getRelativePath($path, $key);
		if(!$image) {
			return false;
		}

		return $this->Html->image($image, $this->_config($config, $key));
	}

/**
 * find an image by its extension
 *
 * @param string $extention the extension to lookup
 *
 * @return string
 */
	public function findByExtention($extention = null, array $config = array()) {
		$images = Configure::read('CoreImages');
		$imageData = array();
		$key = null;
		if (!$extention) {
			$imageData['path'] = $images['path'] . 'folders/' . $images['images']['folders']['empty'];
			$key = 'folder';
		}

		if (empty($imageData)) {
			foreach ($images['images'] as $path => $image) {
				if (isset($image[$extention])) {
					$imageData['path'] = $images['path'] . $path . '/' . $image[$extention];
					$key = $extention;
				}
			}
		}

		if ($extention[0] == '.' || empty($imageData)) {
			$imageData['path'] = $images['path'] . 'unknown/' . $images['images']['unknown']['unknown'];
			$key = 'unknown';
		}

		return $this->Html->image($imageData['path'], $this->_config($config, $key));
	}

/**
 * get a relative path
 *
 * @param string $place the place
 * @param string $key the key
 *
 * @return boolean|string
 */
	public function getRelativePath($place, $key = null) {
		return $this->exists($place, $key, 'relativePath');
	}

/**
 * check if the passed location exists
 *
 * @param type $places
 *
 * @return boolean|array
 */
	public function placeExists($places = null) {
		if (!is_array($places)) {
			$places = array($places);
		}

		$currentPlaces = $this->getPlaces();
		foreach ($places as $k => $place) {
			if (!in_array($place, $currentPlaces)) {
				unset($places[$k]);
			}
		}

		if (empty($places)) {
			$this->errors[] = 'the place(s) does not exist.';
			return false;
		}

		return $places;
	}

/**
 * get a list of the images
 *
 * @return array
 */
	public function getImages() {
		if (!$this->_images) {
			$this->_images = Configure::read('CoreImages.images');
		}

		return $this->_images;
	}

/**
 * get a list of possible locations
 *
 * @return array
 */
	public function getPlaces() {
		if (!$this->_places) {
			$this->_places = array_keys($this->getImages());
		}

		return $this->_places;
	}

/**
 * check if an image exists
 *
 * You can get a return of the following:
 *  - fileName: the file name
 *  - relativePath: the full relative path
 *	- absolutePath: the full path
 *
 * @param string $place the location
 * @param string $key the type
 * @param string $returnType what data is returned
 *
 * @return boolean
 */
	public function exists($place, $key, $returnType = null) {
		$images = $this->getImages();

		if (!isset($images[$place][$key])) {
			$this->errors[] = 'CoreImages.images.' . $place . '.' . $key . ' does not exist';
			return false;
		}

		switch ($returnType) {
			case 'fileName':
				return $images[$place][$key];

			case 'relativePath':
				return Configure::read('CoreImages.path') . $place . '/' . $images[$place][$key];

			case 'absolutePath':
				//  @todo implement this
		}

		return true;
	}

/**
 * get a config for the image tags
 *
 * @param array $config the config to overload with
 *
 * @return array
 */
	protected function _config(array $config, $key = null) {
		$config = array_merge($this->settings, $config);
		if(empty($config['title'])) {
			$config['title'] = $this->niceTitleText($key);
		}

		if(substr($config['title'], -4) == ' :: ') {
			$config['title'] = str_replace(' :: ', '', $config['title']);
		}
		if(empty($config['alt'])) {
			$config['alt'] = $config['title'];
		}

		if(strstr($config['alt'], '  :: ') !== false) {
			$config['alt'] = trim(current(explode(' :: ', $config['alt'])));
		}

		return $config;
	}
}