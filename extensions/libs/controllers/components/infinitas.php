<?php
/**
*/
class InfinitasComponent extends Object {
	var $name = 'Infinitas';

	/**
	* components being used here
	*/
	var $components = array('Session');

	/**
	* Controllers initialize function.
	*/
	function initialize(&$controller, $settings = array()) {
		$this->Controller = &$controller;
		$settings = array_merge(array(), (array)$settings);

		$this->setupCache();
		$this->loadCoreImages();
	}

	/**
	* Setup some default cache settings.
	*
	* This creates some cache configs for the main
	* parts of infinitas.
	*/
	function setupCache() {
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
	function loadCoreImages(){
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
	function changePaginationLimit($options=array(),$params=array()){
		// remove the current / default value
		if (isset($params['named']['limit'])) {
			unset($params['named']['limit']);
		}

		$parmas['named']['limit'] = $this->paginationHardLimit($options['pagination_limit']);

		$this->redirect(
			array(
				'plugin' => $params['plugin'],
				'controller' => $params['controller'],
				'action' => $params['action']
				) + $params['named']
			);
	}

	/**
	* Set a hard limit on pagination.
	*
	* This will stop people requesting to many pages and slowing down the site.
	*
	* @param int $limit the current limit that is being requested
	* @return int site max if limit was to high :: the limit that was set if its not to high
	*/
	function paginationHardLimit($limit = null){
		if ( $limit && $limit > Configure::read('Global.pagination_limit')) {
			$this->Session->setFlash(__('You requested to many records, defaulting to site maximum',true));
			$limit = Configure::read('Global.pagination_limit');
		}
		return (int)$limit;
	}

	/**
	* force the site to use www.
	*
	* this will force your site to use the sub domain www.
	*/
	function forceWwwUrl(){
		// read the host from the server environment
		$host = env('HTTP_HOST');
		if ($host='loaclhost') {
			return true;
		}

		// clean up the host
		$host = strtolower($host);
		$host = trim($host);

		// some apps request with the port
		$host = str_replace(':80', '', $host);
		$host = str_replace(':8080', '', $host);
		$host = trim($host);

		// if the host is not starting with www. redirect the
		// user to the same URL but with www :-)
		if (!strpos($host,'www')){
			$this->redirect('www'.$host);
		}
	}

	/**
	 * Get the correct layout
	 *
	 * @param array $params
	 * @return string the layout to be used when rendering the site.
	 */
	function getCorrectLayout($params=array()){
		if (empty($params)){
			return 'default';
		}

		if ($this->Controller->RequestHandler->isAjax()) {
			return 'ajax';
		}

		$prefix = '';
		if (isset($params['prefix'])) {
			$prefix = $params['prefix'];
		}

		switch ($prefix) {
			case 'admin':
				return 'admin';
				break;

			case 'client':
				return 'client';
				break;

			default:
				return 'default';
		} // switch
	}
}

?>