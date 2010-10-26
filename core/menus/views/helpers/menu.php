<?php
	/**
	 * Menu helper
	 *
	 * Used to generate different types of menus.
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.menus
	 * @subpackage Infinitas.menus.views.helpers.menu
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class MenuHelper extends InfinitasHelper{
		public $helpers = array(
			'Html',
			'Events.Event'
		);

		/**
		 * base structure of admin links
		 */
		private $__adminMenuUrl = array(
			'plugin' => false,
			'controller' => false,
			'action' => false,
			'admin' => true,
			'prefix' => 'admin'
		);

		/**
		 * Base structure of dashboard links
		 */
		private $__adminDashboardIcon = array(
			'name' => '',
			'icon' => 'core/infinitas_thumb.png',
			'dashboard' => '',
			'menus' => array(

			)
		);

		/**
		 * Build the main admin navigation for the current plugin.
		 * 
		 * @return mixed
		 */
		public function builAdminMenu(){
			$return = Cache::read('admin_menu_'.$this->plugin, 'menus');
			if($return !== false){
				return $return;
			}
			$this->__adminMenuUrl['plugin'] = $this->plugin;
			
			$menus = $this->Event->trigger($this->plugin.'.adminMenu');
			$items = isset($menus['adminMenu'][$this->plugin]['main']) ? $menus['adminMenu'][$this->plugin]['main'] : array();			

			$items = array(
				'Home' => '/admin'
			) + $items;

			$return = array();
			foreach($items as $name => $url){
				if(is_array($url)){
					$url = array_merge($this->__adminMenuUrl, $url);
				}

				$options = array(
					'escape' => false
				);
				if($this->here == Router::url($url)){
					$options = array_merge($options, array('class' => 'current'));
				}

				$return[] = $this->Html->link(
					$name,
					$url,
					$options
				);
			}

			unset($menus, $items);
			Cache::write('admin_menu_'.$this->plugin, $return, 'menus');
			return $return;
		}

		/**
		 * Build the icon list for admin dashboard.
		 *
		 * Generates an array of links for plugins to be used as a dashboard
		 * list of icons. If nothing is passed it will build the list for the entire
		 * app (used on admin dashboard).
		 *
		 * @var array $plugins this is the array of icons (could do with a better name)
		 * @var string $type this is a name that is used for cache if not used funy things can happen
		 *
		 * @return array the menu that was built
		 */
		public function builDashboardLinks($plugins = array(), $type = null){
			if(!$type){
				$type = $this->plugin;
			}
			
			if(empty($plugins)){
				$plugins = $this->Event->trigger('pluginRollCall');
				$plugins = array_filter($plugins['pluginRollCall']);
				$type = 'all';
			}
			
			ksort($plugins);

			$cache = Cache::read('dashboard_'.$type, 'menus');
			if($cache !== false){
				return $cache;
			}

			$return = array();
			foreach($plugins as $name => $info) {
				$info = array_merge($this->__adminDashboardIcon, $info);
				if(empty($info['name'])){
					$info['name'] = __(prettyName($name), true);
				}

				if(empty($info['dashboard'])) {
					$info['dashboard'] = array(
						'plugin' => strtolower($name),
						'controller' => strtolower($name),
						'action' => 'index'
					);
				}				

				$var = 'plugin';
				if($type !== 'all'){
					$var = $type;
				}
				
				else if(strstr(App::pluginPath($name), APP.'core'.DS)){
					$var = 'core';
				}				

				$return[$var][] = $this->Html->link(
					$info['name'],
					$info['dashboard'],
					array(
						'title' => sprintf('%s :: %s', __($info['name'], true), __($info['description'], true)),
						'escape' => false,
						'style' => 'background-image: url(' . Router::url(isset($info['icon']) ? $info['icon'] : DS . $name . DS . 'img' . DS . 'icon.png') . ');'
					)
				);
			}
			
			Cache::write('dashboard_'.$type, $return, 'menus');
			unset($plugins);

			return $return;
		}
		
		/**
		 * Create nested list menu.
		 *
		 * this method uses {@see __buildDropdownMenu} to generate a nested list
		 * from the items returned by a db search.
		 *
		 * @param array $data the items from MenuItem::find('all')
		 * @param string $type horizontal || vertical, the type of menu to create.
		 *
		 * @return a nice formated <ul> list
		 */
		public function nestedList($data = array(), $type = 'horizontal'){
			if (empty($data)) {
				$this->errors[] = 'There are no items to make the menu with';
				return false;
			}
			$this->_menuData = '<ul class="pureCssMenu pureCssMenum0">';
				foreach( $data as $k => $v ){
					$this->_menuLevel = 0;
					$this->__buildDropdownMenu($v, 'MenuItem');
				}
			$this->_menuData .= '</ul>';

			return str_replace('</a></a>', '</a>', $this->_menuData);
		}

		/**
		 * create the items in the list.
		 *
		 * @param array $array part of the tree
		 * @param string $model the alias of the model being used
		 *
		 * @return part of the formated tree.
		 */
		private function __buildDropdownMenu($array = array(), $model = ''){
			if (empty($array['MenuItem']) || $model = '') {
				$this->errors[] = 'nothing passed to generate';
				return false;
			}

			$currentCss = $suffix = '';

			if ($this->_menuLevel == 0) {
				$suffix = '0';
			}

			$isSeperator = $array['MenuItem']['name'] == '--' ? true : false;

			if($isSeperator) {
				$array['MenuItem']['item'] = '';
			}

			$linkName = __($array['MenuItem']['name'], true);

			if (!empty($array['children'])) {
				$linkName = '<span>'.$linkName.'</span>';
			}

			$class = 'pureCssMenui'.$suffix;
			if($isSeperator) {
				$class .= ' seperator';
			}

			$this->_menuData .= '<li class="'.$class.'">';
			if(!$isSeperator) {
				$menuLink = $array['MenuItem']['link'];
				if (empty($array['MenuItem']['link'])) {
					$_items = $array['MenuItem'];

					$menuLink['prefix']     = (!empty($array['MenuItem']['prefix'])     ? $array['MenuItem']['prefix']     : null);
					$menuLink['plugin']     = (!empty($array['MenuItem']['plugin'])     ? $array['MenuItem']['plugin']     : null);
					$menuLink['controller'] = (!empty($array['MenuItem']['controller']) ? $array['MenuItem']['controller'] : null);
					$menuLink['action']     = (!empty($array['MenuItem']['action'])     ? $array['MenuItem']['action']     : 'index');
					$menuLink[]             = (!empty($array['MenuItem']['params'])     ? $array['MenuItem']['params']     : null);

					if($menuLink['controller'] == $this->params['controller'] || $menuLink['plugin'] == $this->params['plugin']){
						$currentCss = ' current';
						$this->_currentCssDone = true;
					}

					foreach($menuLink as $key => $value ){
						if (empty($value)) {
							unset($menuLink[$key]);
						}
					}

					if ($array['MenuItem']['force_backend']) {
						$menuLink['admin'] = true;
					}
					else if ($array['MenuItem']['force_frontend']) {
						$menuLink['admin'] = false;
					}
				}

				if(!empty($currentCss) && $this->_currentCssDone === false && Router::url($menuLink) == $this->here){
					$currentCss = ' current';
					$this->_currentCssDone = true;
				}

				$this->_menuData .= $this->Html->link(
					$linkName,
					$menuLink,
					array(
						'class' => 'pureCssMenui'.$suffix.$currentCss,
						'escape' => false
					)
				);

				if (!empty($array['children'])) {
					$this->_menuData .= '<ul class="pureCssMenum">';
						foreach( $array['children'] as $k => $v ){
							$this->_menuLevel = 1;
							$this->__buildDropdownMenu($v, $model);
						}
					$this->_menuData .= '</ul>';
				}
				$this->_menuData .= '</a>';
			}
			else {
				$this->_menuData .= $linkName;
			}

			$this->_menuData .= '</li>';
		}
	 }