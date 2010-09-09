<?php
	App::import('Lib', 'Events.AppEvents');
	class EventCore extends Object{
		/**
		 * Event objects
		 *
		 * @var array
		 */
		private $__eventClasses = array();

		/**
		 * Available handlers and what eventClasses they appear in.
		 *
		 * @var array
		 */
		private $__eventHandlerCache = array();

		/**
		 * Returns a singleton instance of the EventCore class.
		 *
		 * @return EventCore instance
		 * @access public
		 */
		public function &getInstance(){
			static $instance = array();

			if (empty($instance)) {
				$instance[0] =& new EventCore();
				$instance[0]->__loadEventHandlers();
			}
			return $instance[0];
		}

		/**
		 * Trigger an event or array of events
		 *
		 * @param string|array $eventName
		 * @param array $data (optional) Array of data to pass along to the event handler
		 * @return array
		 *
		 */
		public function trigger(&$HandlerObject, $eventName, $data = array()){
			if(!is_array($eventName)){
				$eventName = array($eventName);
			}

			$eventNames = Set::filter($eventName);
			foreach($eventNames as $eventName){
				$eventData = EventCore::__parseEventName($eventName);

				$return[$eventData['event']] = EventCore::__dispatchEvent($HandlerObject, $eventData['scope'], $eventData['event'], $data);
			}

			return $return;
		}

		/**
		 * Loads all available event handler classes for enabled plugins
		 *
		 */
		private function __loadEventHandlers(){
			$this->__eventHandlerCache = Cache::read('event_handlers', 'core');

			if(empty($this->__eventHandlerCache)) {
				$plugins = App::objects('plugin');
				foreach((array)$plugins as $pluginName){
					$filename = App::pluginPath($pluginName) . Inflector::underscore($pluginName) . '_events.php';
					$className = Inflector::camelize($pluginName . '_events');
					if(file_exists($filename)){
						if(EventCore::__loadEventClass($className, $filename)) {
							EventCore::__getAvailableHandlers($this->__eventClasses[$className]);
						}
					}
				}

				Cache::write('event_handlers', $this->__eventHandlerCache, 'core');
			}
		}

		/**
		 * Dispatch Event
		 *
		 * @param string $eventName
		 * @param array $data (optional)
		 * @return array
		 *
		 */
		private function __dispatchEvent(&$HandlerObject, $scope, $eventName, $data = array()){
			$eventHandlerMethod = EventCore::__handlerMethodName($eventName);
			$_this =& EventCore::getInstance();

			$return = array();

			if(isset($_this->__eventHandlerCache[$eventName])){
				foreach($_this->__eventHandlerCache[$eventName] as $eventClass){
					$pluginName = EventCore::__extractPluginName($eventClass);

					if(($scope == 'Global' || $scope == $pluginName)){
						if(!isset($_this->__eventClasses[$eventClass]) || !is_object($_this->__eventClasses[$eventClass])) {
							EventCore::__loadEventClass($eventClass);
						}

						$EventObject = $_this->__eventClasses[$eventClass];

						//$Event = new Event($eventName, $HandlerObject, $pluginName, $data);
						$Event = new Event($eventName, $HandlerObject, $pluginName);

						$return[$pluginName] = call_user_func_array(array(&$EventObject, $eventHandlerMethod), array(&$Event, $data));
					}
				}
			}
			return $return;
		}

		private function __parseEventName($eventName){
			App::import('Core', 'String');


			$eventTokens = String::tokenize($eventName, '.');
			$scope = 'Global';
			$event = $eventTokens[0];
			if (count($eventTokens) > 1){
				list($scope, $event) = $eventTokens;
			}

			return compact('scope', 'event');
		}

		/**
		 * Converts event name into a handler method name
		 *
		 * @param string $eventName
		 * @return string
		 *
		 */
		private function __handlerMethodName($eventName){
			return 'on'.Inflector::camelize($eventName);
		}

		/**
		 * Loads list of available event handlers in a event object
		 *
		 * @param object $Event
		 *
		 */
		private function __getAvailableHandlers(&$Event){
			if(is_object($Event)){
				$_this =& EventCore::getInstance();

				$availableMethods = get_class_methods($Event);

				foreach($availableMethods as $availableMethod){
					if(strpos($availableMethod, 'on') === 0){
						$handlerName = substr($availableMethod, 2);
						$handlerName{0} = strtolower($handlerName{0});
						$_this->__eventHandlerCache[$handlerName][] = get_class($Event);
					}
				}
			}
		}

		/**
		 * Loads and initialises an event class
		 *
		 * @param string $className
		 * @param string $filename
		 *
		 */
		private function __loadEventClass($className, $filename = false){
			if($filename === false) {
				$baseName = Inflector::underscore($className) . '.php';
				$pluginName = Inflector::camelize(preg_replace('/_events.php$/', '', $baseName));
				$pluginPath = App::pluginPath($pluginName);
				$filename = $pluginPath . $baseName;
			}
			
			App::Import('file', $className, true, array(), $filename);

			try{
				$_this =& EventCore::getInstance();

				$_this->__eventClasses[$className] =& new $className();
				return true;
			}
			catch(Exception $e){
				$this->log(serialize($e), 'core');
				return false;
			}
		}

		/**
		 * Extracts the plugin name out of the class name
		 *
		 * @param string $className
		 * @return string
		 *
		 */
		private function __extractPluginName($className){
			return strtolower(substr($className, 0, strlen($className) - 6));
		}
	}

	/**
	 * Event Object
	 */
	class Event {

		/**
		 * Contains assigned values
		 *
		 * @var array
		 *
		protected $values = array();*/

		/**
		 * Constructor with EventName and EventData (optional)
		 *
		 * Event Data is automaticly assigned as properties by array key
		 *
		 * @param string $eventName Name of the Event
		 * @param array $data optional array with k/v data
		 */
		//public function __construct($eventName, &$HandlerObject, $pluginName, $data = array()) {
		public function __construct($eventName, &$HandlerObject, $pluginName) {
			$this->name = $eventName;
			$this->Handler = $HandlerObject;
			$this->plugin = $pluginName;
		}

		/**
		 * Write to object
		 *
		 * @param string $name Key
		 * @param mixed $value Value
		 *
		public function __set1($name, $value) {
			$this->values['eventData'][$name] = $value;
		}

		/**
		 * Read from object
		 *
		 * @param string $name Key
		 *
		public function __get1($name) {
			return $this->values['eventData'][$name];
		}*/
	}