<?php
	/**
	 * Infinitas Helper.
	 *
	 * Does a lot of stuff like generating ordering buttons, load modules and
	 * other things needed all over infinitas.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package libs
	 * @subpackage libs.views.helpers.infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.6a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class InfinitasHelper extends AppHelper{
		var $helpers = array(
			'Html',
			'Form',
			'Libs.Design',
			'Libs.Image'
		);

		/**
		* JSON errors.
		*
		* Set up some errors for json.
		* @access public
		*/
		var $_json_errors = array(
		    JSON_ERROR_NONE      => 'No error',
		    JSON_ERROR_DEPTH     => 'The maximum stack depth has been exceeded',
		    JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
		    JSON_ERROR_SYNTAX    => 'Syntax error',
		);

		var $_menuData = '';

		var $_menuLevel = 0;

		var $external = true;

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
		function loadModules($position = null, $admin = false){
			if (!$position) {
				$this->errors[] = 'No position selected to load modules';
				return false;
			}

			$modules = Cache::read('modules.' . $position . '.' . ($admin ? 'admin' : 'user'), 'core');

			if($modules == false || empty($modules)) {
				$modules = ClassRegistry::init('Management.Module')->getModules($position, $admin);

				Cache::write('modules.' . $position . '.' . ($admin ? 'admin' : 'user'), $modules, 'core');
			}

			$out = '<div class="modules '.$position.'">';
				$currentRoute = Router::currentRoute();

				foreach($modules as $module){
					if ($module['Theme']['name'] != '' && $module['Theme']['name'] != $this->theme) {
						//skip modules that are not for the current theme
						continue;
					}

					$moduleOut = '<div class="module '.$module['Module']['module'].'">';
						if ($module['Module']['show_heading']) {
							$moduleOut .= '<h2>'.__($module['Module']['name'],true).'</h2>';
						}

						if (!empty($module['Module']['module'])) {
							$View = ClassRegistry::getObject('View');
							$path = 'modules/';
							if ($admin) {
								$path .= 'admin/';
							}
							$moduleOut .= $View->element($path.$module['Module']['module'], array('config' => $this->_moduleConfig($module['Module'])), true);
						}
						else if (!empty($module['Module']['content'])) {
							$moduleOut .= $module['Module']['content'];
						}
					$moduleOut .= '</div>';

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
		* Module Config.
		*
		* This method works out params from JSON data in the module. if there is something
		* wrong with the JSON code that is submitted it will return an empty array(), or it
		* will return an array with the config.
		*
		* @access protected
		* @params string $config some JSON data to be decoded.
		*/
		function _moduleConfig($config = ''){
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

		/**
		 * Create nisted list menu.
		 *
		 * this method uses {@see __buildDropdownMenu} to generate a nested list
		 * from the items returned by a db search.
		 *
		 * @param array $data the items from MenuItem::find('all')
		 * @param string $type horizontal || vertical, the type of menu to create.
		 *
		 * @return a nice formated <ul> list
		 */
		function generateDropdownMenu($data = array(), $type = 'horizontal'){
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

			return $this->_menuData;
		}

		/**
		 * create the items in the list.
		 *
		 * @param array $array part of the tree
		 * @param string $model the alias of the model being used
		 *
		 * @return part of the formated tree.
		 */
		function __buildDropdownMenu($array = array(), $model = ''){
			if (empty($array['MenuItem']) || $model = '') {
				$this->errors[] = 'nothing passed to generate';
				return false;
			}

			$suffix = '';
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

				$this->_menuData .= $this->Html->link(
					$linkName,
					$menuLink,
					array(
						'class' => 'pureCssMenui'.$suffix,
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

		/**
		* Create a status icon.
		*
		* Takes a int 0 || 1 and returns a icon with title tags etc to be used
		* in places like admin to show iff something is on/off etc.
		*
		* @param int $status the status tinyint(1) from a db find.
		*
		* @return string some html for the generated image.
		*/
		function status($status = null){
			$image = false;
			$params = array();

			switch (strtolower($status)){
				case 1:
				case 'yes':
				case 'on':
					if ($this->external){
						$params['title'] = __( 'Active', true );
					}

					$image = $this->Html->image(
					    $this->Image->getRelativePath('status', 'active'),
					    $params + array(
					        'width' => '16px',
					        'alt' => __('On', true)
					    )
					);
					break;

				case 0:
				case 'no':
				case 'off':
					if ($this->external){
						$params['title'] = __('Disabled', true);
					}

					$image = $this->Html->image(
					    $this->Image->getRelativePath('status', 'inactive'),
					    $params + array(
					        'width' => '16px',
					        'alt' => __('Off', true)
					    )
					);
					break;
			}

			return $image;
		}

		/**
		 * Create a locked icon.
		 *
		 * takes the data from a find and shows if it is locked and if so who by
		 * and when.
		 *
		 * @param array $item the data
		 * @param mixed $model the model the data is from
		 *
		 * @return mixed some html with the image
		 */
		function locked($item = array(), $model = null){
			if (!$model || empty($item) || empty($item[$model])){
				$this->errors[] = 'you missing some data there.';
				return false;
			}

			switch (strtolower($item[$model]['locked'])){
				case 1:
					$this->Time = new TimeHelper();
					$image = $this->Html->image(
					    $this->Image->getRelativePath('status', 'locked'),
					    array(
					        'alt' => __('Locked', true),
					        'width' => '16px',
					        'title' => sprintf(
					            __( 'This record was locked %s by %s', true ),
					            $this->Time->timeAgoInWords($item[$model]['locked_since']),
					            $item['Locker']['username']
					        )
					    )
					);
					unset($this->Time);
					break;

				case 0:
					$image = $this->Html->image(
					    $this->Image->getRelativePath('status', 'not-locked'),
					    array(
					        'alt' => __('Not Locked', true),
					        'width' => '16px',
					        'title' => __('This record is not locked', true)
					    )
					);
					break;
			}

			return $image;
		}

		/**
		 * Featured icon.
		 *
		 * Creates a featured icon like the status and locked.
		 *
		 * @param array $record the data from find
		 * @param string $model the model alias
		 *
		 * @return string html of the icon.
		 */
		function featured($record = array(), $model = 'Feature'){
			if (empty($record[$model])){
				$this->messages[] = 'This has no featured items.';

				return $this->Html->image(
				    $this->Image->getRelativePath('status', 'not-featured'),
				    array(
				        'alt'   => __('No', true),
				        'title' => __('Not a featured item', true),
				        'width' => '16px'
				    )
				);
			}

			return $this->Html->image(
			    $this->Image->getRelativePath('status', 'featured'),
			    array(
			        'alt'   => __('Yes', true),
			        'title' => __('Featured Item', true),
			        'width' => '16px'
			    )
			);
		}

		function loggedInUserText($counts){
			$allInIsAre    = ($counts['all'] > 1) ? __('are', true) : __('is', true);
			$loggedInIsAre = ($counts['loggedIn'] > 1) ? __('are', true) : __('is', true);
			$guestsIsAre   = ($counts['guests'] > 1) ? __('are', true) : __('is', true);
			$guests        = ($counts['guests'] > 1) ? __('guests', true) : __('a guest', true);

			return '<p>'.
				sprintf(
					__('There %s %s people on the site, %s %s logged in and %s %s %s.', true),
					$allInIsAre, $counts['all'],
					$counts['loggedIn'], $loggedInIsAre,
					$counts['guests'], $guestsIsAre,
					$guests
				).
			'</p><p>&nbsp;</p>';
		}
	}
?>