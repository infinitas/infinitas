<?php
/**
*/
class InfinitasComponent extends Object {
	var $name = 'Infinitas';

	var $defaultLayout = 'default';

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
		$this->setupConfig();
		$this->setupTheme();
		$this->loadCoreImages();
	}

	/**
	* Load config vars from the db.
	*
	* This gets all the config vars from the database and loads them in to the
	* {#see Configure} class to be used later in the app
	*
	* @todo load the users configs also.
	*/
	function setupConfig(){
		$configs = Cache::read('core_configs', 'core');

		if (!$configs) {
			$configs = ClassRegistry::init('Management.Config')->getConfig();
		}

		foreach($configs as $config) {
			Configure::write($config['Config']['key'], $config['Config']['value']);
		}
	}

	/**
	* Setup the theme for the site
	*
	* Gets the current theme set in db and sets if up
	*/
	function setupTheme(){
		$this->Controller->layout = 'front';

		if ( isset( $this->Controller->params['admin'] ) && $this->Controller->params['admin'] ){
			$this->Controller->layout = 'admin';
		}
		if(!$theme = Cache::read('currentTheme')) {
			$theme = ClassRegistry::init('Management.Theme')->getCurrnetTheme();
		}

		if (!isset($theme['Theme']['name'])) {
			$theme['Theme'] = array();
		} else {
			$this->Controller->theme = $theme['Theme']['name'];
		}
		Configure::write('Theme',$theme['Theme']);

		$routes = Cache::read('routes', 'core');

		if (!$routes) {
			$routes = Classregistry::init('Management.Route')->getRoutes();
		}

		$currentRoute = Router::currentRoute();
		if (!empty($routes) && is_object($currentRoute)) {
			foreach( $routes as $route ){
				if ( $route['Route']['url'] == $currentRoute->template && !empty($route['Route']['theme'])) {
					$this->Controller->theme = $route['Route']['theme'];
				}
			}
		}
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

		$parmas['named']['limit'] = $this->paginationHardLimit($options['pagination_limit'],true);

		$this->Controller->redirect(
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
	* setting the Global.pagination_limit to 0 should turn this off
	*
	* @param int $limit the current limit that is being requested
	* @return int site max if limit was to high :: the limit that was set if its not to high
	*/
	function paginationHardLimit($limit = null, $return = false){
		if ( ( $limit && Configure::read('Global.pagination_limit') ) && $limit > Configure::read('Global.pagination_limit')) {
			$this->Session->setFlash(__('You requested to many records, defaulting to site maximum',true));

			$this->Controller->params['named']['limit'] = Configure::read('Global.pagination_limit');
			$url = array(
				'plugin'     => $this->Controller->params['plugin'],
				'controller' => $this->Controller->params['controller'],
				'action'     => $this->Controller->params['action']
			) + $this->Controller->params['named'];

			$this->Controller->redirect($url);
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
		if ($host='localhost') {
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
	* Get the users browser.
	*
	* return string the users browser name or Unknown.
	*/
	function getBrowser(){
		$agent = env( 'HTTP_USER_AGENT' );

		srand((double)microtime() * 1000000);
		$r = rand();
		$u = uniqid(getmypid().$r.(double)microtime() * 1000000, 1);
		$m = md5 ( $u );


		if (
			preg_match( "/msie[\/\sa-z]*([\d\.]*)/i", $agent, $m ) &&
			!preg_match( "/webtv/i", $agent ) &&
			!preg_match( "/omniweb/i", $agent ) &&
			!preg_match( "/opera/i", $agent )
		) {
			// IE
			return 'MS Internet Explorer '.$m[1];
		}

		else if (preg_match( "/netscape.?\/([\d\.]*)/i", $agent, $m )){
				// Netscape 6.x, 7.x ...
				return 'Netscape '.$m[1];
		}

		else if (
			preg_match( "/mozilla[\/\sa-z]*([\d\.]*)/i", $agent, $m ) &&
			!preg_match( "/gecko/i", $agent ) &&
			!preg_match( "/compatible/i", $agent ) &&
			!preg_match( "/opera/i", $agent ) &&
			!preg_match( "/galeon/i", $agent ) &&
			!preg_match( "/safari/i", $agent )
		) {
			// Netscape 3.x, 4.x ...
			return 'Netscape '.$m[1];
		}

		else{
			// Other
			Configure::load('browsers');
			$browsers      = Configure::read('Browsers');
			foreach ( $browsers as $key => $value){
				if ( preg_match( '/'.regexEscape($value).'.?\/([\d\.]*)/i', $agent, $m ) ){
					return $browsers[$key].' '.$m[1];
					break;
				}
			}
		}

		return 'Unknown';
	}

	/**
	 * Get users opperating system.
	 *
	 * @return string the name of the opperating sustem or Unknown if unable to detect
	 */
	function getOperatingSystem(){
		$agent = env( 'HTTP_USER_AGENT' );
		Configure::load('operating_systems');
		$operatingSystems = Configure::read('OperatingSystems');

		foreach ( $operatingSystems as $key => $value){
			if ( preg_match( "/$value/i", $agent ) ){
				return $operatingSystems[$key];
			}
		}

		return 'Unknown';
	}

	/**
	* Find users country.
	*
	* Attempt to get the country the user is from.  returns unknown if its not
	* able to match something.
	*/
	function getCountry($ipAddress = null, $code = false){
		if (!$ipAddress){
			$ipAddress = $this->Controller->RequestHandler->getClientIP();
			if (!$ipAddress) {
				return array( 'code' => '', 'name' => '' );
			}
		}

		App::import('Lib', 'Libs.Geoip/inc.php');
		$countryDataFile = dirname(dirname(dirname(__FILE__))).DS.'libs'.DS.'geoip'.DS.'country.dat';
		if (!is_file($countryDataFile)) {
			return false;
		}

		$data = geoip_open($countryDataFile, GEOIP_STANDARD);

		$country = geoip_country_name_by_addr($data, $ipAddress);
		if (empty($country)) {
			$country = 'Unknown';
		}

		if ($code) {
			$code = geoip_country_code_by_addr( $data, $ip_address );
			if (empty($code)) {
				$code = 'Unknown';
			}

			geoip_close($data);

			return array(
				'code' => $code,
				'country' => $country
			);
		}

		geoip_close($data);

		return $country;
	}

	function getCity(){
		return 'TODO';
	}
}

?>