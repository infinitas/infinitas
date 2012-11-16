<?php
	class ServerStatusEvents extends AppEvents {
		public function onAdminMenu(Event $Event) {
			$menu['main']['Dashboard'] = array('controller' => 'server_status', 'action' => 'dashboard');
			switch($Event->Handler->params['controller']) {
				case 'php':
					$menu['main']['Php Info'] = array('controller' => 'php', 'action' => 'info');
					$menu['main']['APC'] = array('controller' => 'php', 'action' => 'apc');
					break;
			}

			return $menu;
		}

		public function onSetupRoutes(Event $Event) {
			InfinitasRouter::connect(
				'/admin/server_status',
				array(
					'plugin' => 'server_status',
					'controller' => 'server_status',
					'action' => 'dashboard',
					'admin' => true,
					'prefix' => 'admin'
				)
			);
		}

/**
 * Return the server status for IRC bots
 * 
 * @param Event $Event The event being run
 * @param array $data the message data being parsed
 * 
 * @return boolean
 */
	public function onIrcMessage(Event $Event, $data = null) {
		if($data['command'] != 'server') {
			return;
		}

		$memoryUsage = memoryUsage(false);
		$memoryUsage['load'] = implode(' ', serverLoad(false));
		$data['message'] = explode(' ', $data['message'], 2);

		$message = ':to: Memory: :current Load: :load';
		if($data['args']) {
			$message = ':to: Invalid option, try: !server [current|max|load]';
			if($data['args'] == 'help') {
				$message = ':to: !server [current|max|load]';
			}
			if(array_key_exists($data['args'], $memoryUsage)) {
				$message = ':to: :' . $data['args'];
			}
		}

		$Event->Handler->reply($data['channel'], $message, array_merge(
			array('to' => $data['to']),
			$memoryUsage
		));

		return true;
	}
		
}