<?php
	/**
	 * Helper for generating menu markup.
	 *
	 * Menu helper is used for generating different types of menus. From the
	 * dashboard icons found in the admin backend to nested lists for the
	 * frontend.
	 *

	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Menus.Helper
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
	 */
	App::uses('InfinitasHelper', 'Libs.View/Helper');

	class MenuHelper extends InfinitasHelper {
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
			'options' => array(),
			'menus' => array()
		);

		/**
		 * Build the main admin navigation for the current plugin.
		 *
		 * @return mixed
		 */
		public function builAdminMenu() {
			$this->__adminMenuUrl['plugin'] = $this->request->plugin;
			$menus = $this->Event->trigger($this->plugin . '.adminMenu');
			$items = (isset($menus['adminMenu'][$this->plugin]['main'])) ? $menus['adminMenu'][$this->plugin]['main'] : array();

			$return = array();
			foreach ($items as $name => $url) {
				if (is_array($url)) {
					$url = array_merge($this->__adminMenuUrl, $url);
				}

				$options = array(
					'escape' => false
				);
				if ($this->here == Router::url($url)) {
					$options = array_merge($options, array('class' => 'current'));
				}

				$return[] = $this->Html->link(
					$name,
					$url,
					$options
				);
			}

			unset($menus, $items);
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
		 * @deprecated cache is causing loads of issues, and not enough speed.
		 *
		 * @return array
		 */
		public function builDashboardLinks($plugins = array(), $type = null, $cache = true) {
			if (!$type) {
				$type = $this->plugin;
			}

			if (empty($plugins)) {
				$plugins = $this->Event->trigger('pluginRollCall');
				$plugins = array_filter($plugins['pluginRollCall']);
				$type = 'all';
			}

			ksort($plugins);

			$return = array();
			foreach ($plugins as $name => $info) {
				$plugin = Inflector::underscore($name);
				$name = Inflector::camelize($name);
				$info = array_merge($this->__adminDashboardIcon, $info);
				if (empty($info['name'])) {
					$info['name'] = __d($plugin, prettyName($name));
				}

				if (empty($info['dashboard'])) {
					$info['dashboard'] = array(
						'plugin' => $plugin,
						'controller' => $plugin,
						'action' => 'index'
					);
				}

				$var = 'plugin';
				if ($type !== 'all') {
					$var = $type;
				} else if (strstr(InfinitasPlugin::path($name), APP . 'Core' . DS)) {
					$var = 'core';
				}

				$info['title'] = sprintf('%s :: %s', __d($plugin, $info['name']), __d($plugin, $info['description']));
				$info['icon'] = isset($info['icon']) ? $info['icon'] : DS . $name . DS . 'img' . DS . 'icon.png';

				$class = null;
				if (!empty($info['dashboard']['plugin']) && $info['dashboard']['plugin'] == $this->request->params['plugin']) {
					 $class = 'active';
				}
				$return[$var][] = $this->Html->link(implode('', array(
					$this->Html->image($info['icon']),
					$this->Html->tag('p', $info['name'])
				)), $info['dashboard'], array(
					'title' => $info['title'],
					'escape' => false,
					'class' => array(
						'thumbnail',
						$class
					)
				));;
			}

			unset($plugins);

			return $return;
		}

		public function bootstrapNav($menus, $ulOptions = array(), $child = false) {
			foreach ($menus as &$menu) {
				$ulOptions = array(
					'class' => 'nav'
				);
				$linkOptions = array(
					'escape' => false
				);
				$hasChildren = !empty($menu['children']);

				if ($child && !$hasChildren) {
					$ulOptions = array(
						'class' => 'dropdown-menu'
					);
				}

				$caret = null;
				if ($hasChildren) {
					$linkOptions = array_merge(array(
						'class' => 'dropdown-toggle',
						'data-toggle' => 'dropdown'
					), $linkOptions);
					$caret = $this->Html->tag('b', '', array('class' => 'caret'));
				}
				$url = $this->url($menu);
				if ($menu['MenuItem']['name'] == '--') {
					$menu['MenuItem']['name'] = $this->Html->tag('li', '', array('class' => 'divider'));
					$hasChildren = false;
				} else if ($menu['MenuItem']['class'] == 'nav-header') {
					$menu['MenuItem']['name'] = $this->Html->tag('li', $menu['MenuItem']['name'], array('class' => 'nav-header'));
				} else {
					$menu['MenuItem']['name'] = $this->Html->link($menu['MenuItem']['name'] . $caret, $url, $linkOptions);
				}

				if ($hasChildren) {
					$menu['MenuItem']['name'] .= self::bootstrapNav($menu['children'], array(), true);
				}

				$menu = $this->Html->tag('li', $menu['MenuItem']['name'], array(
					'class' => array(
						$this->here == $url ? 'active' : null,
						$hasChildren ? 'dropdown' : null
					)
				));
			}

			$menus = $this->Html->tag('ul', implode('', $menus), $ulOptions);
			if ($child) {
				return $menus;
			}
			return $this->Html->tag('div', $menus, array(
				'class' => 'nav-collapse collapse'
			));
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
		public function nestedList($data = array(), $config = array()) {
			if (empty($data)) {
				$this->errors[] = 'There are no items to make the menu with';
				return false;
			}

			$config = array_merge(
				array('class' => null, 'id' => null),
				$config
			);

			$this->_menuData = String::insert('<ul class=":class" id=":id">', $config);
				foreach ( $data as $k => $v ) {
					$this->_menuLevel = 0;
					$this->__buildDropdownMenu($v, 'MenuItem');
				}
			$this->_menuData .= '</ul>';

			return str_replace('</a></a>', '</a>', $this->_menuData);
		}

		/**
		 * build a link from a menu item in the database
		 *
		 * @param array $data the data from a menuItem find
		 * @param array $config configs for the link, @see HtmlHelper::link()
		 *
		 * @return string
		 */
		public function link($data, $config = array()) {
			$url = InfinitasRouter::url($this->url($data));

			if (empty($config)) {
				$config = array('class' => $data['MenuItem']['class']);
			}

			else if (empty($config['class'])) {
				$config['class'] = $data['MenuItem']['class'];
			}
			else {
				$config['class'] .= ' ' . $data['MenuItem']['class'];
			}

			return $this->Html->link($data['MenuItem']['name'], $url, $config);
		}

		/**
		 * generate a url from a menu item
		 *
		 * @throws Exception
		 *
		 * @param array $data the data from the find
		 *
		 * @return string|array
		 */
		public function url($data = array(), $full = null) {
			if (empty($data['MenuItem'])) {
				throw new Exception('Menu item is not valid');
			}

			if (!empty($data['MenuItem']['link'])) {
				return $data['MenuItem']['link'];
			}

			unset($data['MenuItem']['params']['data-attr']);

			$url = array_merge(
				array(
					'plugin' => $data['MenuItem']['plugin'],
					'controller' => $data['MenuItem']['controller'],
					'action' => $data['MenuItem']['action'],
					//'prefix' => $data['MenuItem']['prefix'],
				),
				$data['MenuItem']['params']
			);

			if ($data['MenuItem']['force_backend']) {
				$url['admin'] = true;
			}

			else if ($data['MenuItem']['force_frontend']) {
				$url['admin'] = false;
			}

			return $url;
		}

		/**
		 * create the items in the list.
		 *
		 * @param array $array part of the tree
		 * @param string $model the alias of the model being used
		 *
		 * @return part of the formated tree.
		 */
		private function __buildDropdownMenu($array = array(), $model = '', $config = array()) {
			if (empty($array['MenuItem']) || $model = '') {
				$this->errors[] = 'nothing passed to generate';
				return false;
			}

			$currentCss = $suffix = '';

			if ($this->_menuLevel == 0) {
				$suffix = '0';
			}

			$isSeperator = ($array['MenuItem']['name'] == '--') ? true : false;

			if ($isSeperator) {
				$array['MenuItem']['item'] = '';
			}

			$linkName = __d('menus', $array['MenuItem']['name']);

			if (!empty($array['children'])) {
				$linkName = '<span>'.$linkName.'</span>';
			}

			$cssClass = 'pureCssMenui';
			$class = 'pureCssMenui'.$suffix;
			if ($isSeperator) {
				$class .= ' seperator';
			}

			$this->_menuData .= '<li class="'.$class.'">';
			if (!$isSeperator) {
				$menuLink = $this->url($array);

				if ($this->_currentCssDone === false && Router::url($menuLink) == $this->here) {
					$currentCss = ' current';
					$this->_currentCssDone = true;
				}

				$this->_menuData .= $this->Html->link(
					$linkName,
					InfinitasRouter::url($menuLink),
					array(
						'class' => String::insert(':subClass:suffix:currentCss', array('subClass' => $cssClass, 'suffix' => $suffix, 'currentCss' => $currentCss)),
						'escape' => false,
						'data-description' => !empty($array['MenuItem']['params']['data-attr']['description']) ? $array['MenuItem']['params']['data-attr']['description'] : '',
						'target' => is_string($menuLink) && !in_array($menuLink{0}, array('/', '#')) ? '_blank' : '_self'
					)
				);

				if (!empty($array['children'])) {
					$this->_menuData .= '<ul class="pureCssMenum">';
					foreach ($array['children'] as $k => $v) {
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