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
	App::uses('InfinitasComponent', 'Libs/Component');

	class ThemesComponent extends InfinitasComponent {
		public function beforeRender(Controller $Controller) {
			parent::beforeRender($Controller);
			
			$event = $Controller->Event->trigger($Controller->plugin.'.setupThemeStart');
			if (isset($event['setupThemeStart'][$Controller->plugin])) {
				if (is_string($event['setupThemeStart'][$Controller->plugin])) {
					$Controller->theme = $event['setupThemeStart'][$Controller->plugin];
					return true;
				}

				else if ($event['setupThemeStart'][$Controller->plugin] === false) {
					return false;
				}
			}

			$Controller->layout = 'front';
			$theme = Cache::read('currentTheme');
			if($theme === false) {
				$theme = ClassRegistry::init('Themes.Theme')->getCurrentTheme();
			}
			
			if(!empty($theme['Theme']['default_layout'])) {
				$Controller->layout = $theme['Theme']['default_layout'];
			}

			if (isset($Controller->request->params['admin']) && $Controller->request->params['admin']){
				$Controller->layout = 'admin';
			}

			$event = $Controller->Event->trigger(
				$Controller->plugin . '.setupThemeLayout',
				array(
					'layout' => $Controller->layout,
					'params' => $Controller->request->params
				)
			);
			
			if (isset($event['setupThemeLayout'][$Controller->plugin]) && is_string($event['setupThemeLayout'][$Controller->plugin])) {
				$Controller->layout = $event['setupThemeLayout'][$Controller->plugin];
			}

			if (!isset($theme['Theme']['name'])) {
				$theme['Theme'] = array('name' => null);
			}
			else {
				$event = $Controller->Event->trigger(
					$Controller->plugin . '.setupThemeSelector',
					array(
						'theme' => $theme['Theme'],
						'params' => $Controller->request->params
					)
				);
				
				if (isset($event['setupThemeSelector'][$Controller->plugin])) {
					if (is_array($event['setupThemeSelector'][$Controller->plugin])) {
						$theme['Theme'] = $event['setupThemeSelector'][$Controller->plugin];
						if (!isset($theme['Theme']['name'])) {
							$this->cakeError('eventError', array('message' => 'The theme is invalid.', 'event' => $event));
						}
					}
				}
			}
			
			$Controller->theme = $theme['Theme']['name'];
			Configure::write('Theme', $theme['Theme']);

			$event = $Controller->Event->trigger($Controller->plugin.'.setupThemeRoutes', array('params' => $Controller->request->params));
			if (isset($event['setupThemeRoutes'][$Controller->plugin]) && !$event['setupThemeRoutes'][$Controller->plugin]) {
				return false;
			}

			if (empty($routes)) {
				$routes = Classregistry::init('Routes.Route')->getRoutes();
			}

			$currentRoute = Router::currentRoute(Configure::read('CORE.current_route'));
			if (!empty($routes) && is_object($currentRoute)) {
				foreach($routes as $route) {
					if ($route['Route']['url'] == $currentRoute->template) {
						if(!empty($route['Route']['theme'])) {
							$Controller->theme = $route['Route']['theme'];
						}
						
						if(!empty($route['Route']['layout'])) {
							$Controller->layout = $route['Route']['layout'];
						}
					}
				}
			}

			$event = $Controller->Event->trigger($Controller->plugin.'.setupThemeEnd', array('theme' => $Controller->theme, 'params' => $Controller->request->params));
			if (isset($event['setupThemeEnd'][$Controller->plugin])) {
				if (is_string($event['setupThemeEnd'][$Controller->plugin])) {
					$Controller->theme = $event['setupThemeEnd'][$Controller->plugin];
				}
			}

			return true;
		 }
	
		public function actionAdminGetThemeLayouts() {
			if(empty($this->Controller->request->data[$this->Controller->modelClass]['theme'])) {
				$this->Controller->set('json', false);
			}
			
			$this->Controller->set('json', InfinitasTheme::layouts($this->Controller->request->data[$this->Controller->modelClass]['theme']));
		}
	}