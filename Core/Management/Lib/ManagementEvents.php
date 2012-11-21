<?php
	class ManagementEvents extends AppEvents {
		public function onPluginRollCall(Event $Event) {
		}

		public function onSetupCache(Event $Event) {
			return array(
				'name' => 'core',
				'config' => array(
					'prefix' => 'core.',
				)
			);
		}

		public function onSlugUrl(Event $Event, $data = null, $type = null) {
			switch($data['type']) {
				case 'comments':
					return array(
						'plugin' => 'management',
						'controller' => 'infinitas_comments',
						'action' => $data['data']['action'],
						'id' => $data['data']['id'],
						'category' => 'user-feedback'
					);
					break;
			}
		}

		public function onSetupRoutes(Event $Event) {
			InfinitasRouter::connect(
				'/admin',
				array(
					'plugin' => 'management',
					'controller' => 'management',
					'action' => 'dashboard',
					'prefix' => 'admin',
					'admin' => 1,
				)
			);

			InfinitasRouter::connect(
				'/admin/management',
				array(
					'plugin' => 'management',
					'controller' => 'management',
					'action' => 'site',
					'admin' => true,
					'prefix' => 'admin'
				)
			);
		}

		public function onGetRequiredFixtures(Event $Event) {
			return array(
				'Management.Ticket',
				'Management.Session'
			);
		}
	}