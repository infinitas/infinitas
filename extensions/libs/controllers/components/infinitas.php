<?php
/**
*/
class InfinitasComponent extends Object {
	//var $name = 'Infinitas';
	var $components = array();
	/**
	* Controllers initialize function.
	*/
	function initialize(&$controller, $settings = array()) {
		$this->Controller = &$controller;
		$settings = array_merge(array(), (array)$settings);

		$this->__setupCache();
		$this->__loadCoreImages();
	}

	/**
	* Setup some default cache settings.
	*
	* This creates some cache configs for the main
	* parts of infinitas.
	*/
	function __setupCache() {
		Cache::config(
			'cms',
			array(
				'engine' => 'File',
				'duration' => 3600,
				'probability' => 100,
				'prefix' => '',
				'lock' => false,
				'serialize' => true,
				'path' => CACHE . 'cms'
				)
			);

		Cache::config(
			'core',
			array(
				'engine' => 'File',
				'duration' => 3600,
				'probability' => 100,
				'prefix' => '',
				'lock' => false,
				'serialize' => true,
				'path' => CACHE . 'core'
				)
			);

		Cache::config(
			'blog',
			array(
				'engine' => 'File',
				'duration' => 3600,
				'probability' => 100,
				'prefix' => '',
				'lock' => false,
				'serialize' => true,
				'path' => CACHE . 'blog'
				)
			);
	}

	/**
	* Load core images.
	*
	* This is where all the images for the site is loaded.
	*/
	function __loadCoreImages(){
		Configure::load('images');
	}

	/**
	* Change the Pagination dropdown.
	*
	* This is what allows you to view different number of records in the
	* index pages.
	*
	* @param array $options
	* @return
	*/
	function __changePaginationLimit($options=array(),$params=array()){
		// remove the current / default value
		if (isset($params['named']['limit'])) {
			unset($params['named']['limit']);
		}

		$params['named']['limit'] = $options['pagination_limit'];

		$this->redirect(
			array(
				'plugin' => $params['plugin'],
				'controller' => $params['controller'],
				'action' => $params['action']
				) + $params['named']
			);
	}

	function __forceWwwUrl(){

	}


}

?>