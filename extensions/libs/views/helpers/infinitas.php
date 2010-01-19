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
				foreach($modules as $module){
					$out .= '<div class="module '.$module['Module']['module'].'">';
						if ($module['Module']['show_heading']) {
							$out .= '<h2>'.__($module['Module']['name'],true).'</h2>';
						}

						if (!empty($module['Module']['module'])) {
							$View = ClassRegistry::getObject('view');
							$out .= $View->element('modules/'.$module['Module']['module']);
						}
						else if (!empty($module['Module']['content'])) {
							$out .= $module['Module']['content'];
						}
					$out .= '</div>';
				}
			$out .= '</div>';

			return $out;
		}
	}
?>