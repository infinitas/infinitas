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