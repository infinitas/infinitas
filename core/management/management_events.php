<?php
	final class ManagementEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Setup',
				'description' => 'Configure Your site',
				'icon' => '/management/img/icon.png',
				'author' => 'Infinitas'
			);
		}
		
		public function onSetupConfig(){
			return Configure::load('management.config');
		}

		public function onSetupCache(){
			return array(
				'name' => 'core',
				'config' => array(
					'duration' => 3600,
					'probability' => 100,
					'prefix' => 'core.',
					'lock' => false,
					'serialize' => true
				)
			);
		}

		public function onAdminMenu(&$event){
			return array();
		}

		public function onSlugUrl(&$event, $data){
			switch($data['type']){
				case 'comments':
					return array(
						'plugin' => 'management',
						'controller' => 'comments',
						'action' => $data['data']['action'],
						'id' => $data['data']['id'],
						'category' => 'user-feedback'
					);
					break;
			} // switch
		}

		public function onSetupRoutes(){
			/**
			 * frontend urls
			 */
			Router::connect('/login', array('plugin' => 'management', 'controller' => 'users', 'action' => 'login'));
			Router::connect('/logout', array('plugin' => 'management', 'controller' => 'users', 'action' => 'logout'));
			Router::connect('/register', array('plugin' => 'management', 'controller' => 'users', 'action' => 'register'));
			Router::connect('/activate-account', array('plugin' => 'management', 'controller' => 'users', 'action' => 'activate'));
			Router::connect('/forgot-password', array('plugin' => 'management', 'controller' => 'users', 'action' => 'forgot_password'));
			Router::connect('/reset-password', array('plugin' => 'management', 'controller' => 'users', 'action' => 'reset_password'));

			/**
			 * admin urls
			 */
			Router::connect('/admin/login', array('plugin' => 'management', 'controller' => 'users', 'action' => 'login', 'admin' => true, 'prefix' => 'admin'));
			Router::connect('/admin/logout', array('plugin' => 'management', 'controller' => 'users', 'action' => 'logout', 'admin' => true, 'prefix' => 'admin'));
			Router::connect('/admin', array('plugin' => 'management', 'controller' => 'management', 'action' => 'dashboard', 'admin' => true, 'prefix' => 'admin'));
		}
	}