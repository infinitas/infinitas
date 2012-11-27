<?php
	class UsersEvents extends AppEvents {
		public function onPluginRollCall(Event $Event) {
			return array(
				'name' => 'Users',
				'description' => 'Manage your community',
				'icon' => 'user',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'dashboard'),
			);
		}

		public function onSetupCache(Event $Event) {
			return array(
				'name' => 'users',
				'config' => array(
					'prefix' => 'users.',
				)
			);
		}

		public function onAdminMenu(Event $Event) {
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'dashboard'),
				'Users' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'index'),
				'Groups' => array('plugin' => 'users', 'controller' => 'groups', 'action' => 'index'),
				'Current Visitors' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'logged_in')
			);

			return $menu;
		}

		public function onSetupRoutes(Event $Event) {
			/**
			 * frontend urls
			 */
			InfinitasRouter::connect('/profile', array('plugin' => 'users', 'controller' => 'users', 'action' => 'view'));
			InfinitasRouter::connect('/login', array('plugin' => 'users', 'controller' => 'users', 'action' => 'login'));
			InfinitasRouter::connect('/logout', array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout'));
			InfinitasRouter::connect('/register', array('plugin' => 'users', 'controller' => 'users', 'action' => 'register'));
			InfinitasRouter::connect('/activate-account', array('plugin' => 'users', 'controller' => 'users', 'action' => 'activate'));
			InfinitasRouter::connect('/forgot-password', array('plugin' => 'users', 'controller' => 'users', 'action' => 'forgot_password'));
			InfinitasRouter::connect('/reset-password', array('plugin' => 'users', 'controller' => 'users', 'action' => 'reset_password'));

			/**
			 * admin urls
			 */
			InfinitasRouter::connect('/admin/users/users/index', array('plugin' => 'users', 'controller' => 'users', 'action' => 'index', 'admin' => true, 'prefix' => 'admin'));
			InfinitasRouter::connect('/admin/users', array('plugin' => 'users', 'controller' => 'users', 'action' => 'dashboard', 'admin' => true, 'prefix' => 'admin'));
			InfinitasRouter::connect('/admin/login', array('plugin' => 'users', 'controller' => 'users', 'action' => 'login', 'admin' => true, 'prefix' => 'admin'));
			InfinitasRouter::connect('/admin/logout', array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout', 'admin' => true, 'prefix' => 'admin'));
		}

		public function onRequireComponentsToLoad(Event $Event) {
			return array(
				'Users.Visitor'
			);
		}

		public function onGetRequiredFixtures(Event $Event) {
			return array(
				'Users.User',
				'Users.Group',
			);
		}

	}