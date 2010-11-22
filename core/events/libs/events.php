<?php
	App::import('Lib', 'Events.AppEvents');

	EventCore::getInstance();

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
		 * a cache of the event names
		 *
		 * @var object
		 * @access public
		 */
		public $eventNameCache;

		/**
		 * a cache of the handler names
		 *
		 * @var object
		 * @access public
		 */
		public $handlerNameCache;

		/**
		 * a cache of the plugin names
		 * 
		 * @var object
		 * @access public
		 */
		public $pluginNameCache;

		/**
		 * Returns a singleton instance of the EventCore class.
		 *
		 * @return EventCore instance
		 * @access public
		 */
		public function getInstance(){
			static $instance = array();

			if (empty($instance)) {
				$instance[0] = new EventCore();
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
						EventCore::__loadEventClass($eventClass);
						$Event = new Event($eventName, $HandlerObject, $pluginName);

						$return[$pluginName] = call_user_func_array(array($_this->__eventClasses[$eventClass], $eventHandlerMethod), array($Event, $data));
					}
				}
			}
			return $return;
		}

		/**
		 * takes the event name and breaks it down into the different parts setting
		 * some defaults for gloabl events. results are cached to avoid importing
		 * and alling the string class to much.
		 * 
		 * @param string $eventName the name of the event
		 * @return array the scope + event name
		 */
		private function __parseEventName($eventName){
			$_this =& EventCore::getInstance();
			
			if(!isset($_this->eventNameCache->{$eventName})){
				App::import('Core', 'String');

				$eventTokens = String::tokenize($eventName, '.');
				$scope = 'Global';
				$event = $eventTokens[0];
				if (count($eventTokens) > 1){
					list($scope, $event) = $eventTokens;
				}
				
				$_this->eventNameCache->{$eventName}  = array(
					'scope' => $scope,
					'event' => $event
				);
			}

			return $_this->eventNameCache->{$eventName};
		}

		/**
		 * Converts event name into a handler method name and caches the result
		 * so that there are less calls to the inflector method.
		 *
		 * @param string $eventName
		 * @return string the method to be called
		 *
		 */
		private function __handlerMethodName($eventName){
			$_this =& EventCore::getInstance();
			if(!isset($_this->handlerNameCache->{$eventName})){
				$_this->handlerNameCache->{$eventName} = 'on' . Inflector::camelize($eventName);
			}
			
			return $_this->handlerNameCache->{$eventName};
		}

		/**
		 * Loads list of available event handlers in a event object
		 *
		 * @param object $Event
		 * @access private
		 */
		private function __getAvailableHandlers($Event){
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
		 * @param string $className the event class to load
		 * @param string $filename the file name of the event
		 * @access private
		 */
		private function __loadEventClass($className, $filename = false){
			$_this =& EventCore::getInstance();
			if(isset($_this->__eventClasses[$className]) && is_object($_this->__eventClasses[$className])) {
				return true;
			}
			
			if($filename === false) {
				$baseName = Inflector::underscore($className) . '.php';
				$pluginName = Inflector::camelize(preg_replace('/_events.php$/', '', $baseName));
				$pluginPath = App::pluginPath($pluginName);
				$filename = $pluginPath . $baseName;
			}
			App::Import('file', $className, true, array(), $filename);

			try{
				$_this->__eventClasses[$className] =& new $className();
				return true;
			}
			catch(Exception $e){
				$this->log(serialize($e), 'core');
				return false;
			}
		}

		/**
		 * Extracts the plugin name out of the class name and caches the value
		 * so that the strtolower and other stuff does not need to be called
		 * so many times.
		 *
		 * @param string $className the name of the class being called
		 * @return string the plugin being called.
		 * @access private
		 */
		private function __extractPluginName($className){
			$_this =& EventCore::getInstance();
			if(!isset($_this->pluginNameCache->{$className})){
				$_this->pluginNameCache->{$className} = strtolower(substr($className, 0, strlen($className) - 6));
			}
			
			return $_this->pluginNameCache->{$className};
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
		public function __construct($eventName, &$HandlerObject, $pluginName) {
			$this->name = $eventName;
			$this->Handler = $HandlerObject;
			$this->plugin = $pluginName;
		}
	}