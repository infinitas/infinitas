<?php
	/**
	 *
	 *
	 */
	class InfinitasHelper extends AppHelper{
		var $_json_errors = array(
		    JSON_ERROR_NONE      => 'No error',
		    JSON_ERROR_DEPTH     => 'The maximum stack depth has been exceeded',
		    JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
		    JSON_ERROR_SYNTAX    => 'Syntax error',
		);

		var $_menuData = '';
		var $_menuLevel = 0;

		function loadModules($position = null, $admin = false){
			if (!$position) {
				$this->errors[] = 'No position selected to load modules';
				return false;
			}

			$modules = ClassRegistry::init('Management.Module')->getModules($position, $admin);

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
							$View = ClassRegistry::getObject('view');
							$path = 'modules/';
							if ($admin) {
								$path .= 'admin/';
							}
							$moduleOut .= $View->element($path.$module['Module']['module'], array('config' => $this->_moduleConfig($module['Module'])));
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

		function generateMenu($data = array(), $type = 'horizontal'){
			if (empty($data)) {
				$this->errors[] = 'There are no items to make the menu with';
				return false;
			}
			$this->_menuData = '<ul class="pureCssMenu pureCssMenum0">';
				foreach( $data as $k => $v ){
					$this->_menuLevel = 0;
					$this->__buildMenu($v, 'MenuItem');
				}
			$this->_menuData .= '</ul>';

			return $this->_menuData;
		}

		function __buildMenu($array = array(), $model = ''){
			if (empty($array['MenuItem']) || $model = '') {
				$this->errors[] = 'nothing passed to generate';
				return false;
			}

			$suffix = '';
			if ($this->_menuLevel == 0) {
				$suffix = '0';
			}

			$linkName = __($array['MenuItem']['name'], true);
			if (!empty($array['children'])) {
				$linkName = '<span>'.$linkName.'</span>';
			}

			$this->_menuData .= '<li class="pureCssMenui'.$suffix.'">';
				$this->_menuData .= $this->Html->link(
					$linkName,
					$array['MenuItem']['link'],
					array(
						'class' => 'pureCssMenui'.$suffix,
						'escape' => false
					)
				);

				if (!empty($array['children'])) {
					$this->_menuData .= '<ul class="pureCssMenum">';
						foreach( $array['children'] as $k => $v ){
							$this->_menuLevel = 1;
							$this->__buildMenu($v, $model);
						}
					$this->_menuData .= '</ul>';
				}

			$this->_menuData .= '</li>';
		}


	}
?>