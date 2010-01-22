<?php
	/**
	 *
	 *
	 */
	class InfinitasHelper extends AppHelper{
		function loadModules($position = null){
			if (!$position) {
				$this->errors[] = 'No position selected to load modules';
				return false;
			}

			$modules = ClassRegistry::init('Management.Module')->getModules($position);

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
							$moduleOut .= $View->element('modules/'.$module['Module']['module']);
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
	}
?>