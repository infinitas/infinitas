<?php
	final class RoutesEvents extends AppEvents {
		public function onPluginRollCall(){
			return array(
				'name' => 'Routes',
				'description' => 'Route pretty urls to your code',
				'icon' => '/routes/img/icon.png',
				'author' => 'Infinitas'
			);
		}

		public function onSetupCache(){
			return array(
				'name' => 'routes',
				'config' => array(
					'prefix' => 'core.routes.'
				)
			);
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Routes' => array('plugin' => 'routes', 'controller' => 'routes', 'action' => 'index'),
				'Active' => array('plugin' => 'routes', 'controller' => 'routes', 'action' => 'index', 'Route.active' => 1),
				'Disabled' => array('plugin' => 'routes', 'controller' => 'routes', 'action' => 'index', 'Route.active' => 0)
			);

			return $menu;
		}

		public function onGetRequiredFixtures($event){
			return array(
				'Routes.Route',
			);
		}
	}