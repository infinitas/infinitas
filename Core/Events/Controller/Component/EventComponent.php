<?php
	class EventComponent extends InfinitasComponent {
		/**
		 * Controller Instance
		 * @var object
		 */
		public $Controler = null;

		/**
		 * Startup
		 *
		 * @param object $controller
		 *
		 */
		public function initialize($controller) {
			$this->test = $controller;
			if(!empty($this->Controller->request->params['plugin']) && !EventCore::isPluginActive($this->Controller->request->params['plugin'])){
				//pr($Controller->request->params);
				//throw new Exception('Plugin not installed');
			}
		}

		/**
		 * Trigger a event
		 *
		 * @param string $eventName Name of the event to trigger
		 * @param array $data Data to pass to event handler
		 * @return array:
		 */
		public function trigger($eventName, $data = array()) {
			return EventCore::trigger($this->test, $eventName, $data);
		}
	}