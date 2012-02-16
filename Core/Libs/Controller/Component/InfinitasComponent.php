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
	class InfinitasComponent extends Component {
		public $defaultLayout = 'default';

		/**
		* Risk is calculated on bad logins vs the number of times that username
		* has been blocked. the higher the risk is the longer the lock out time
		* will be.
		*/
		public $risk = 0;

		/**
		* components being used here
		*/
		public $components = array(
			'Themes.Themes',
			'Events.Event'
		);

		public $configs = array();

		public $Controller = null;

		/**
		* Controllers initialize function.
		*/
		public function initialize($Controller) {
			$this->Controller = $Controller;

			Configure::write('CORE.current_route', Router::currentRoute());

			$this->__registerPlugins();

			$this->__paginationRecall();

			if (Configure::read('Website.force_www')) {
				$this->forceWwwUrl();
			}

			return true;
		}

		private function __registerPlugins() {
			/**
			 * wysiwyg editors
			 */
			$wysiwygEditors = Cache::read('wysiwyg_editors', 'core');
			if($wysiwygEditors === false) {
				$eventData = $this->Event->trigger('registerWysiwyg');
				if(is_array($eventData) && !empty($eventData)){
					$editors = implode(',', current($eventData));
					Cache::write('wysiwyg_editors', $editors, 'core');
				}
			}
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
		public function changePaginationLimit($options=array(),$params=array()){
			// remove the current / default value
			if (isset($params['named']['limit'])) {
				unset($params['named']['limit']);
			}

			$params['named']['limit'] = $this->paginationHardLimit($options['pagination_limit'], true);

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
		public function paginationHardLimit($limit = null, $return = false){
			if ( ( $limit && Configure::read('Global.pagination_limit') ) && $limit > Configure::read('Global.pagination_limit')) {
				$this->Controller->request->params['limit'] = Configure::read('Global.pagination_limit');

				$this->Controller->notice(
					__('You requested to many records, defaulting to site maximum'),
					array(
						'redirect' => array(
							'plugin'	 => $this->Controller->request->params['plugin'],
							'controller' => $this->Controller->request->params['controller'],
							'action'	 => $this->Controller->request->params['action']
						) + (array)$this->Controller->params['named']
					)
				);
			}

			return (int)$limit;
		}

		/**
		 * force the site to use www.
		 *
		 * this will force your site to use the sub domain www.
		 */
		public function forceWwwUrl(){
			// read the host from the server environment
			$host = env('HTTP_HOST');
			if ($host == 'localhost') {
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
			if (!strpos($host, 'www')){
				$this->redirect('www' . $host);
			}
		}

		/**
		 * Get the users browser.
		 *
		 * return string the users browser name or Unknown.
		 */
		public function getBrowser(){
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
				$browsers	  = Configure::read('Browsers');
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
		public function getOperatingSystem(){
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

		public function getPluginAssets() {
			$event = $this->Controller->Event->trigger('requireJavascriptToLoad', $this->Controller->params);
			if(isset($event['requireJavascriptToLoad']['assets'])){
				$libs['assets'] = $event['requireJavascriptToLoad']['assets'];
				$event['requireJavascriptToLoad'] = $libs + $event['requireJavascriptToLoad'];
			}

			if(is_array($event) && !empty($event)) {
				$this->Controller->addJs(current($event));
			}

			$libs = array();
			$event = $this->Controller->Event->trigger('requireCssToLoad', $this->Controller->params);
			if(isset($event['requireCssToLoad']['libs'])) {
				$libs['libs'] = $event['requireCssToLoad']['libs'];
				$event['requireCssToLoad'] = $libs + $event['requireCssToLoad'];
			}

			if(is_array($event) && !empty($event)) {
				$this->Controller->addCss(current($event));
			}
		}

		/**
		 * Moving MPTT records
		 *
		 * This is used for moving mptt records and is called by admin_reorder.
		 */
		public function treeMove(){
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
				$this->Controller->notice(
					__('Nothing found to move'),
					array(
						'redirect' => false
					)
				);
				return false;
			}

			$message = false;

			switch($this->Controller->request->params['direction']) {
				case 'up':
					$message = __('The record was moved up');
					if (!$this->Controller->{$model}->moveUp($this->Controller->{$model}->id, abs(1))) {
						$message = __('Unable to move the record up');
					}
					else {
						$this->Controller->{$model}->afterSave(false);
					}
					break;
				case 'down':
					$message = __('The record was moved down');
					if (!$this->Controller->{$model}->moveDown($this->Controller->{$model}->id, abs(1))) {
						$message = __('Unable to move the record down');
					}
					else {
						$this->Controller->{$model}->afterSave(false);
					}
					break;
				default:
					$message = __('Error occured reordering the records');
					break;
			} // switch

			$this->Controller->notice($message, array('redirect' => false));

			return true;
		}

		/**
		 * Moving records that actas sequenced
		 *
		 * This is used for moving sequenced records and is called by admin_reorder.
		 */
		public function orderedMove(){
			$modelName = $this->Controller->modelClass;

			$orderable = isset($this->Controller->$modelName->actsAs['Libs.Sequence']['order_field']) &&
				!empty($this->Controller->$modelName->actsAs['Libs.Sequence']['order_field']);

			if ($orderable) {
				$this->Controller->request->data[$modelName][$this->Controller->$modelName->actsAs['Libs.Sequence']['order_field']] =
					$this->Controller->params['named']['position'];
			}
			else{
				$this->Controller->request->data[$modelName]['ordering'] = $this->Controller->params['position'];
			}

			if (!$this->Controller->{$modelName}->save($this->Controller->request->data)) {
				$this->Controller->notice(
					__('The record could not be moved'),
					array(
						'redirect' => false
					)
				);
			}

			return true;
		}

		/**
		 * Pagination Recall CakePHP Component
		 * Copyright (c) 2008 Matt Curry
		 * www.PseudoCoder.com
		 *
		 * @author	  mattc <matt@pseudocoder.com>
		 * @version	 1.0
		 * @license	 MIT
		 */
		private function __paginationRecall(){
			$paramsUrl = isset($this->Controller->params['url']) ? $this->Controller->params['url'] : array();

			$options = array();
			//$options = array_merge($this->Controller->params, $paramsUrl, $this->Controller->passedArgs);

			$vars = array('page', 'sort', 'direction', 'limit');
			$keys = array_keys($options);
			$count = count($keys);

			for ($i = 0; $i < $count; $i++) {
				if (!in_array($keys[$i], $vars)) {
					unset($options[$keys[$i]]);
				}
			}

			//$this->addToPaginationRecall($options);
		}

		public function addToPaginationRecall($options = array(), $controller = null) {
			if(!$controller) {
				$controller = $this->Controller;
			}
			
			if ($options) {
				if ($controller->Session->check("Pagination.{$controller->modelClass}.options")) {
					$options = array_merge($controller->Session->read("Pagination.{$controller->modelClass}.options"), $options);
				}

				$controller->Session->write("Pagination.{$controller->modelClass}.options", $options);
			}

			//recall previous options
			if ($controller->Session->check("Pagination.{$controller->modelClass}.options")) {
				$options = $controller->Session->read("Pagination.{$controller->modelClass}.options");
				$controller->passedArgs = array_merge($controller->passedArgs, $options);
			}
		}

		/**
		* Temp acl things
		 */
		protected function _getClassMethods($ctrlName = null) {
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
			if (is_array($properties) && array_key_exists('scaffold', $properties)) {
				if($properties['scaffold'] == 'admin') {
					$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
				}
				/*else {
				   $methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
				   }*/
			}

			return $methods;
		}

		protected function _isPlugin($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) > 1) {
				return true;
			} else {
				return false;
			}
		}

		protected function _getPluginControllerPath($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) == 2) {
				return $arr[0] . '.' . $arr[1];
			} else {
				return $arr[0];
			}
		}

		protected function _getPluginName($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) == 2) {
				return $arr[0];
			} else {
				return false;
			}
		}

		protected function _getPluginControllerName($ctrlName = null) {
			$arr = String::tokenize($ctrlName, '/');
			if (count($arr) == 2) {
				return $arr[1];
			} else {
				return false;
			}
		}

		private function __getClassName() {
			if (isset($this->request->params['plugin'])) {
				return Inflector::classify($this->request->params['plugin']) . '.' . Inflector::classify($this->Controller->name);
			} else {
				return Inflector::classify($this->Controller->name);
			}
		}

		protected function _getPlugins(){
			$plugins = array(
				'infinitas',
				'extentions',
				'plugins'
			);
			$return = array();
			foreach($plugins as $plugin ){
				$return = array_merge($return, $this->_getPluginControllerNames($plugin));
			}

			return $return;
		}

		protected function _getPluginControllerNames($plugin) {
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

		public function checkDbVersion() {
			App::import('Lib', 'Migrations.MigrationVersion');

			$Version = new MigrationVersion();

			$currentVersion = $Version->getVersion('app');
			$latestVersion = end($Version->getMapping('app'));

			if($currentVersion < $latestVersion['version']) {
				$this->Controller->redirect(array('plugin' => 'installer', 'controller' => 'upgrade', 'action' => 'index', 'admin' => true));
			}
		}
	}