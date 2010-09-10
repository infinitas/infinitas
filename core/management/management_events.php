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
			Router::connect(
				'/s/:code',
				array(
					'plugin' => 'management',
					'controller' => 'short_urls',
					'action' => 'go'
				),
				array(
					'pass' => array('code'),
					'code' => '[0-9a-zA-Z]+'
				)
			);
		}
	}