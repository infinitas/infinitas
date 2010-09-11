<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	 class ModuleLoaderHelper extends InfinitasHelper{
		/**
		 * Skip modules.
		 *
		 * Add the names of modules you want to skip to this var and they will not
		 * be loaded.
		 *
		 * @var array
		 */
		public $moduleIgnoreOverload = array();

		/**
		 * Module Loader.
		 *
		 * This is used to load modules. it generates a wrapper div with the class
		 * set as the module name for easy styleing, and will create a header if set
		 * in the backend.
		 *
		 * @params string $position this is the possition that is to be loaded, can be anything from the database
		 * @params bool $admin if true its a admin module that should be loaded.
		 */
		public function load($position = null, $admin = false){
			if (!$position) {
				$this->errors[] = 'No position selected to load modules';
				return false;
			}

			$modules = Cache::read('modules.' . $position . '.' . ($admin ? 'admin' : 'user'), 'core');
			if(empty($modules)) {
				$modules = ClassRegistry::init('Modules.Module')->getModules($position, $admin);
			}

			/**
			 * @todo if no modules based on possition, possition is a module -> $this->loadModule($position)
			 */

			$currentRoute = Configure::read('CORE.current_route');

			$out = '<div class="modules '.$position.'">';

				foreach($modules as $module){
					if (
						($module['Theme']['name'] != '' && $module['Theme']['name'] != $this->theme) ||
						in_array($module['Module']['module'], $this->moduleIgnoreOverload)
					) {
						//skip modules that are not for the current theme
						continue;
					}
					$params = $this->__getModuleParams($module, $admin);
					if($params === false){
						continue; // from userland and its not active
					}

					$moduleOut = $this->loadModule($module['Module']['module'], $params);

					if (!empty($module['Route']) && is_object($currentRoute)){
						foreach($module['Route'] as $route){
							if ($route['url'] == $currentRoute->template) {
								$out .= $moduleOut;
							}
						}
					}

					else if (empty($module['Route'])) {
						$out .= $moduleOut;
					}

				}
			$out .= '</div>';

			return $out;
		}

		/**
		 * Load single modules.
		 *
		 * This is used by the core module loader, and to load single modules. This
		 * can be handy when you just want to load a particular module inside a view.
		 *
		 * @params string $position this is the possition that is to be loaded, can be anything from the database
		 * @params bool $admin if true its a admin module that should be loaded.
		 */
		public function loadModule($module = null, $params = array()){
			if(!$module){
				return false;
			}

			if($params == null){
				$params = $this->__getModuleParams($module);
			}

			$class = isset($params['config']['class']) ? $params['config']['class'] : '';
			$id = isset($params['config']['id']) ? $params['config']['id'] : '';
			$moduleOut = '<div class="module '.str_replace('/', '-', $module).' '.$class.'">';
				if ($params['title']) {
					$moduleOut .= '<h2><a id="'.$id.'" href="#">'.__($params['title'],true).'</a></h2>';
				}

				if (!empty($module)) {
					$path = 'modules/';

					$this->__getViewClass();
					$moduleOut .= $this->View->element(
						$path.$module,
						$params,
						true
					);


				}
				else if ($params['content']) {
					$moduleOut .= $params['content'];
				}
			$moduleOut .= '</div>';

			return $moduleOut;
		}

		/**
		 * Get the params for the module being loaded, If loadModule is called
		 * from user land, they will not have the details of the module for it
		 * to load properly, and instead of making them do the db call manualy,
		 * it is done here automaticaly
		 *
		 * @param mixed $module string from userland, array (like find(first)) from core
		 * @param bool $admin affects the path the module is loaded from.
		 *
		 * @return array the params for loading the module.
		 */
		private function __getModuleParams($module, $admin = null){
			if(!$admin){
				$admin = isset($this->params['admin']) ? $this->params['admin'] : false;
			}

			if(is_string($module)){
				$module = ClassRegistry::init('Modules.Module')->getModule($module, $admin);
				if(empty($module)){
					return false;
				}
			}

			return array(
				'plugin' => $module['Module']['plugin'],
				'title' => $module['Module']['show_heading'] ? $module['Module']['name'] : false,
				'config' => $this->__getModuleConfig($module['Module']),
				'content' => !empty($module['Module']['content']) ? $module['Module']['content'] : false,
				'admin' => $admin
			);
		}

		/**
		* Module Config.
		*
		* This method works out params from JSON data in the module. if there is something
		* wrong with the JSON code that is submitted it will return an empty array(), or it
		* will return an array with the config.
		*
		* @access protected
		* @params string $config some JSON data to be decoded.
		*/
		private function __getModuleConfig($config = ''){
			if (empty($config['config'])) {
				return array();
			}

			$json = json_decode($config['config'], true);

			if (!$json) {
				$this->errors[] = 'module ('.$config['name'].'): '.$this->_json_errors[json_last_error()];
				return array();
			}

			return $json;
		}

		private function __getViewClass(){
			if(!$this->View){
				$this->View = &ClassRegistry::getObject('view');
			}
		}
	}