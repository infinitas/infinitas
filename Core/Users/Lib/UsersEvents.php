<?php
	final class UsersEvents extends AppEvents {
		public function onPluginRollCall(){
			return array(
				'name' => 'Users',
				'description' => 'Manage your community',
				'icon' => '/users/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'dashboard'),
			);
		}

		public function onSetupCache(){
			return array(
				'name' => 'users',
				'config' => array(
					'prefix' => 'users.',
				)
			);
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'dashboard'),
				'Users' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'index'),
				'Groups' => array('plugin' => 'users', 'controller' => 'groups', 'action' => 'index'),
				'Current Visitors' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'logged_in')
			);

			return $menu;
		}

		public function onSetupRoutes(){
			/**
			 * frontend urls
			 */
			Router::connect('/profile', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'view'));
			Router::connect('/login', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'login'));
			Router::connect('/logout', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'logout'));
			Router::connect('/register', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'register'));
			Router::connect('/activate-account', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'activate'));
			Router::connect('/forgot-password', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'forgot_password'));
			Router::connect('/reset-password', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'reset_password'));

			/**
			 * admin urls
			 */
			Router::connect('/admin/users', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'dashboard', 'admin' => true, 'prefix' => 'admin'));
			Router::connect('/admin/users/users/index', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'index', 'admin' => true, 'prefix' => 'admin'));
			Router::connect('/admin/login', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'login', 'admin' => true, 'prefix' => 'admin'));
			Router::connect('/admin/logout', array('plugin' => 'Users', 'controller' => 'users', 'action' => 'logout', 'admin' => true, 'prefix' => 'admin'));
		}

		public function onRequireComponentsToLoad($events){
			return array(
				'Users.Visitor'
			);
		}

		public function onGetRequiredFixtures($event){
			return array(
				'Users.User',
				'Users.Group',
			);
		}
	}