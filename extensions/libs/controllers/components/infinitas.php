<?php
	/**
	 * InfinitasComponent
	 *
	 * @package
	 * @author dogmatic
	 * @copyright Copyright (c) 2010
	 * @version $Id$
	 * @access public
	 */
	class InfinitasComponent extends Object {
		var $name = 'Infinitas';

		var $defaultLayout = 'default';

		/**
		* Risk is calculated on bad logins vs the number of times that username
		* has been blocked. the higher the risk is the longer the lock out time
		* will be.
		*/
		var $risk = 0;

		/**
		* components being used here
		*/
		var $components = array('Session');

		var $configs = array();

		/**
		* Controllers initialize function.
		*/
		function initialize(&$controller, $settings = array()) {
			$this->Controller = &$controller;
			$settings = array_merge(array(), (array)$settings);

			$this->Controller->Event->trigger('setupCache');

			$this->setupConfig();

			$this->__checkBadLogins();
			$this->__ipBlocker();

			$this->setupTheme();
			$this->loadCoreImages();

			if (Configure::read('Website.force_www')) {
				$this->forceWwwUrl();
			}
		}

		/**
		 * Set up system configuration.
		 *
		 * Load the default configuration and check if there are any configs
		 * to load from the current plugin. configurations can be completely rewriten
		 * or just added to.
		 */
		function setupConfig(){
			$this->configs = Cache::read('core_configs', 'core');

			if (!$this->configs) {
				$this->configs = ClassRegistry::init('Management.Config')->getConfig();
			}

			$eventData = $this->Controller->Event->trigger($this->Controller->plugin.'.setupConfigStart', $this->configs);
			if (isset($eventData['setupConfigStart'][$this->Controller->plugin])){
				$this->configs = (array)$eventData['setupConfigStart'][$this->Controller->plugin];

				if (!array($this->configs)) {
					$this->cakeError('eventError', array('message' => 'Your config is wrong.', 'event' => $eventData));
				}
			}

			$eventData = $this->Controller->Event->trigger($this->Controller->plugin.'.setupConfigEnd');
			if (isset($eventData['setupConfigEnd'][$this->Controller->plugin])){
				$this->configs = $this->configs + (array)$eventData['setupConfigEnd'][$this->Controller->plugin];
			}

			if (!$this->_writeConfigs()) {
				$this->cakeError('configError', array('message' => 'Config was not written'));
			}
		}

		/**
		 * Write the configuration.
		 *
		 * Write all the config values that have been called found in InfinitasComponent::setupConfig()
		 */
		function _writeConfigs(){
			if (empty($this->configs)) {
				return false;
			}

			foreach($this->configs as $config) {
				if (!(isset($config['Config']['key']) || isset($config['Config']['value']))) {
					continue;
				}
				Configure::write($config['Config']['key'], $config['Config']['value']);
			}

			return true;
		}

		/**
		* Setup the theme for the site
		*
		* Gets the current theme set in db and sets if up
		*/
		function setupTheme(){
			$event = $this->Controller->Event->trigger($this->Controller->plugin.'.setupThemeStart');
			if (isset($event['setupThemeStart'][$this->Controller->plugin])) {
				if (is_string($event['setupThemeStart'][$this->Controller->plugin])) {
					$this->Controller->theme = $event['setupThemeStart'][$this->Controller->plugin];
					return true;
				}

				else if ($event['setupThemeStart'][$this->Controller->plugin] === false) {
					return false;
				}
			}

			$this->Controller->layout = 'front';

			if (isset($this->Controller->params['admin'] ) && $this->Controller->params['admin']){
				$this->Controller->layout = 'admin';
			}

			$event = $this->Controller->Event->trigger($this->Controller->plugin.'.setupThemeLayout', array('layout' => $this->Controller->layout, 'params' => $this->Controller->params));
			if (isset($event['setupThemeLayout'][$this->Controller->plugin])) {
				if (is_string($event['setupThemeLayout'][$this->Controller->plugin])) {
					$this->Controller->layout = $event['setupThemeLayout'][$this->Controller->plugin];
				}
			}

			if(!$theme = Cache::read('currentTheme')) {
				$theme = ClassRegistry::init('Management.Theme')->getCurrentTheme();
			}

			if (!isset($theme['Theme']['name'])) {
				$theme['Theme'] = array();
			}
			else {
				$event = $this->Controller->Event->trigger($this->Controller->plugin.'.setupThemeSelector', array('theme' => $theme['Theme'], 'params' => $this->Controller->params));
				if (isset($event['setupThemeSelector'][$this->Controller->plugin])) {
					if (is_array($event['setupThemeSelector'][$this->Controller->plugin])) {
						$theme['Theme'] = $event['setupThemeSelector'][$this->Controller->plugin];
						if (!isset($theme['Theme']['name'])) {
							$this->cakeError('eventError', array('message' => 'The theme is invalid.', 'event' => $event));
						}
					}
				}
			}
			$this->Controller->theme = $theme['Theme']['name'];
			Configure::write('Theme', $theme['Theme']);

			$event = $this->Controller->Event->trigger($this->Controller->plugin.'.setupThemeRoutes', array('params' => $this->Controller->params));
			if (isset($event['setupThemeRoutes'][$this->Controller->plugin]) && !$event['setupThemeRoutes'][$this->Controller->plugin]) {
				return false;
			}

			$routes = Cache::read('routes', 'core');
			if (empty($routes)) {
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

			$event = $this->Controller->Event->trigger($this->Controller->plugin.'.setupThemeEnd', array('theme' => $this->Controller->theme, 'params' => $this->Controller->params));
			if (isset($event['setupThemeEnd'][$this->Controller->plugin])) {
				if (is_string($event['setupThemeEnd'][$this->Controller->plugin])) {
					$this->Controller->theme = $event['setupThemeEnd'][$this->Controller->plugin];
				}
			}
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

			$params['named']['limit'] = $this->paginationHardLimit($options['pagination_limit'],true);

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
			$event = $this->Controller->Event->trigger('findBrowser');
			if (isset($event['findBrowser'][$this->Controller->plugin]) && is_string($event['findBrowser'][$this->Controller->plugin])) {
				return $event['findBrowser'][$this->Controller->plugin];
			}

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
			$event = $this->Controller->Event->trigger('findOperatingSystem');
			if (isset($event['findOperatingSystem'][$this->Controller->plugin]) && is_string($event['findOperatingSystem'][$this->Controller->plugin])) {
				return $event['findOperatingSystem'][$this->Controller->plugin];
			}

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

		/**
		* Get the city the user is in.
		*/
		function getCity(){
			return 'TODO';
		}

		/**
		* Block people.
		*
		* Will get a list of ip addresses that are saved to be blocked and
		* if the user matches that address they will be black holed.
		*
		* If the user is allowed it is saved to their session so that the test
		* is not done on every request.
		*/
		function __ipBlocker(){
			if ($this->Controller->Session->read('Infinitas.Security.ip_checked')) {
				return true;
			}

			$ips = Cache::read('blocked_ips', 'core');
			if (!$ips) {
				$ips = ClassRegistry::init('Management.IpAddress')->getBlockedIpAddresses();
			}

			$currentIp = $this->Controller->RequestHandler->getClientIp();

			if(in_array($currentIp, $ips)) {
				$this->Controller->Security->blackHole($this->Controller, 'ipAddressBlocked');
			}

			else {
				foreach($ips as $ip) {
					if(eregi($ip, $currentIp)) {
						$this->Controller->Security->blackHole($this->Controller, 'ipAddressBlocked');
					}
				}
			}

			$this->Controller->Session->write('Infinitas.Security.ip_checked', true);

			return true;
		}

		/**
		 * Record bad logins.
		 *
		 * This will record each time a user tries to log in with the incorect
		 * username / password combination.
		 *
		 * @param array $data the username and password form the login atempt.
		 * @return true
		 */
		function badLoginAttempt($data){
			$old = (array)$this->Controller->Session->read('Infinitas.Security.loginAttempts');
			$old[] = $data;
			$this->Controller->Session->write('Infinitas.Security.loginAttempts', $old);
			$this->Controller->Session->delete('Infinitas.Security.ip_checked');
			return true;
		}

		/**
		 * Check the bad logins.
		 *
		 * If the bad logins are more than the system allows the user will be band.
		 *
		 * @return true or blackHole;
		 */
		function __checkBadLogins(){
			$old = $this->Controller->Session->read('Infinitas.Security.loginAttempts');

			if (count($old) > 0) {
				$this->risk = ClassRegistry::init('Management.IpAddress')->findSimmilarAttempts(
					$this->Controller->RequestHandler->getClientIp(),
					$this->Controller->data['User']['username']
				);
			}

			if (count($old) >= Configure::read('Security.login_attempts')) {

				ClassRegistry::init('Management.IpAddress')->blockIp(
					$this->Controller->RequestHandler->getClientIp(),
					$this->Controller->Session->read('Infinitas.Security.loginAttempts'),
					$this->risk
				);

				$this->Controller->Security->blackHole($this->Controller, 'invalidLogin');
			}

			return true;
		}

		/**
		* Set up Auth.
		*
		* Define some things that auth needs to work
		*/
		function _setupAuth(){
			$this->Controller->Auth->allow('*');
			//$this->Auth->allowedActions = array('display', 'login', 'logout');

			if (!isset($this->Controller->params['prefix']) || $this->Controller->params['prefix'] != 'admin') {
				$this->Controller->Auth->allow('*');
			}
			$this->Controller->Auth->actionPath   = 'controllers/';
			$this->Controller->Auth->authorize    = 'actions';
			$this->Controller->Auth->loginAction  = array('plugin' => 'management', 'controller' => 'users', 'action' => 'login');

			$this->Controller->Auth->autoRedirect = false;
			$this->Controller->Auth->loginRedirect = '/';

			if (isset($this->Controller->params['prefix']) && $this->Controller->params['prefix'] == 'admin') {
				$this->Controller->Auth->loginRedirect = '/admin';
			}

			$this->Controller->Auth->logoutRedirect = '/';
			$this->Controller->Auth->userModel = 'Management.User';
		}

		/**
		 * setup Security
		 *
		 * settings for security
		 */
		function _setupSecurity(){
			$this->Controller->Security->blackHoleCallback = 'blackHole';
			$this->Controller->Security->validatePost = false;
		}

		/**
		* Set some data for the infinitas js lib.
		*/
		function _setupJavascript(){
			$infinitasJsData['base']	   = (isset($this->Controller->base) ? $this->Controller->base : '');
			$infinitasJsData['here']	   = (isset($this->Controller->here) ? $this->Controller->here : '');
			$infinitasJsData['plugin']     = (isset($this->Controller->plugin) ? $this->Controller->plugin : '');
			$infinitasJsData['name']	   = (isset($this->Controller->name) ? $this->Controller->name : '');
			$infinitasJsData['action']     = (isset($this->Controller->action) ? $this->Controller->action : '');
			$params                        = (isset($this->Controller->params) ? $this->Controller->params : '');
			unset($params['_Token']);
			$infinitasJsData['params']     = $params;
			$infinitasJsData['passedArgs'] = (isset($this->Controller->passedArgs) ? $this->Controller->passedArgs : '');
			$infinitasJsData['data']	   = (isset($this->Controller->data) ? $this->Controller->data : '');
			$infinitasJsData['model']	   = $this->Controller->modelClass;

			$infinitasJsData['config']     = Configure::getInstance();

			$this->Controller->set(compact('infinitasJsData'));
		}

		/**
		 * Moving MPTT records
		 *
		 * This is used for moving mptt records and is called by admin_reorder.
		 */
		function _treeMove(){
			$model = $this->Controller->modelClass;
			$check = $this->Controller->{$model}->find(
				'first',
				array(
					'fields' => array($model.'.id'),
					'conditions' => array($model.'.id' => $this->Controller->$model->id),
					'recursive' => -1
				)
			);

			if (empty($check)) {
				$this->Controller->Session->setFlash(__('Nothing found to move', true));
				return false;
			}

			switch($this->Controller->params['named']['direction']){
				case 'up':
					$this->Controller->Session->setFlash(__('The record was moved up', true));
					if (!$this->Controller->{$model}->moveUp($this->Controller->{$model}->id, abs(1))) {
						$this->Controller->Session->setFlash(__('Unable to move the record up', true));
					}
					else {
						$this->Controller->{$model}->afterSave(false);
					}
					break;
				case 'down':
					$this->Controller->Session->setFlash(__('The record was moved down', true));
					if (!$this->Controller->{$model}->moveDown($this->Controller->{$model}->id, abs(1))) {
						$this->Controller->Session->setFlash(__('Unable to move the record down', true));
					}
					else {
						$this->Controller->{$model}->afterSave(false);
					}
					break;
				default:
					$this->Controller->Session->setFlash(__('Error occured reordering the records', true));
					break;
			} // switch
			return true;
		}

		/**
		 * Moving records that actas sequenced
		 *
		 * This is used for moving sequenced records and is called by admin_reorder.
		 */
		function _orderedMove(){
			$modelName = $this->Controller->modelClass;

			if (isset($this->Controller->$modelName->actsAs['Libs.Sequence']['order_field']) && !empty($this->Controller->$modelName->actsAs['Libs.Sequence']['order_field'])) {
				$this->Controller->data[$modelName][$this->Controller->$modelName->actsAs['Libs.Sequence']['order_field']] = $this->Controller->params['named']['possition'];
			}

			else{
				$this->Controller->data[$modelName]['ordering'] = $this->Controller->params['named']['possition'];
			}

			if (!$this->Controller->{$modelName}->save($this->Controller->data)) {
				$this->Controller->Session->setFlash(__('The record could not be moved', true));
			}

			return true;
		}




		/**
		* Temp acl things
		 */
		function _getClassMethods($ctrlName = null) {
			App::import('Controller', $ctrlName);
			if (strlen(strstr($ctrlName, '.')) > 0) {
				// plugin's controller
				$num = strpos($ctrlName, '.');
				$ctrlName = substr($ctrlName, $num+1);
			}
			$ctrlclass = $ctrlName . 'Controller';
			$methods = get_class_methods($ctrlclass);

			// Add scaffold defaults if scaffolds are being used
			$properties = get_class_vars($ctrlclass);
			if (is_array($properties) && array_key_exists('scaffold',$properties)) {
				if($properties['scaffold'] == 'admin') {
					$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
				}
				/*else {
				   $methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
				   }*/
			}

			return $methods;
		}

		function _isPlugin($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) > 1) {
				return true;
			} else {
				return false;
			}
		}

		function _getPluginControllerPath($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) == 2) {
				return $arr[0] . '.' . $arr[1];
			} else {
				return $arr[0];
			}
		}

		function _getPluginName($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) == 2) {
				return $arr[0];
			} else {
				return false;
			}
		}

		function _getPluginControllerName($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) == 2) {
				return $arr[1];
			} else {
				return false;
			}
		}

		function __getClassName() {
			if (isset($this->params['plugin'])) {
				return Inflector::classify($this->params['plugin']) . '.' . Inflector::classify($this->Controller->name);
			} else {
				return Inflector::classify($this->Controller->name);
			}
		}

		function _getPlugins(){
			$plugins = array(
				'infinitas',
				'extentions',
				'plugins'
			);
			$return = array();
			foreach($plugins as $plugin ){
				$return = array_merge($return, $this->Infinitas->_getPluginControllerNames($plugin));
			}

			return $return;
		}

		function _getPluginControllerNames($plugin) {
			App::import('Core', 'File', 'Folder');
			$paths = Configure::getInstance();
			$folder =& new Folder();
			$folder->cd(APP . $plugin);

			$Plugins = $folder->read();
			$Plugins = $Plugins[0];

			$arr = array();

			// Loop through the plugins
			foreach($Plugins as $pluginName) {
				// Change directory to the plugin
				$didCD = $folder->cd(APP . $plugin. DS . $pluginName . DS . 'controllers');
				// Get a list of the files that have a file name that ends
				// with controller.php
				$files = $folder->findRecursive('.*_controller\.php');

				// Loop through the controllers we found in the plugins directory
				foreach($files as $fileName) {
					// Get the base file name
					$file = basename($fileName);

					// Get the controller name
					$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
					if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
						if (!App::import('Controller', $pluginName.'.'.$file)) {
							debug('Error importing '.$file.' for plugin '.$pluginName);
						} else {
							/// Now prepend the Plugin name ...
							// This is required to allow us to fetch the method names.
							$arr[] = Inflector::humanize($pluginName) . "/" . $file;
						}
					}
				}
			}
			return $arr;
		}

		function checkDbVersion() {
			App::import('Lib', 'Migrations.MigrationVersion');

			$Version = new MigrationVersion();

			$currentVersion = $Version->getVersion('app');
			$latestVersion = end($Version->getMapping('app'));

			if($currentVersion < $latestVersion['version']) {
				$this->Controller->redirect(array('plugin' => 'installer', 'controller' => 'upgrade', 'action' => 'index', 'admin' => true));
			}
		}
	}