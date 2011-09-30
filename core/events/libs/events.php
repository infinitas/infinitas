<?php
	App::import('Lib', 'Events.AppEvents');

	EventCore::getInstance();

	class EventCore {
		/**
		 * Event objects
		 *
		 * @var array
		 */
		protected $_eventClasses = array();

		/**
		 * Available handlers and what eventClasses they appear in.
		 *
		 * @var array
		 */
		protected $_eventHandlerCache = array();

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

		private $__availablePlugins = array();

		private $__installedPlugins = array();

		private function __construct(){}

		private function __clone(){}

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
				$instance[0]->_loadEventHandlers();
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
		static public function trigger(&$HandlerObject, $eventName, $data = array()){
			$_this = EventCore::getInstance();

			if(!is_array($eventName)){
				$eventName = array($eventName);
			}

			$eventNames = Set::filter($eventName);
			$return = array();
			foreach($eventNames as $eventName){
				$eventData = EventCore::_parseEventName($eventName);
				$return[$eventData['event']] = EventCore::_dispatchEvent($HandlerObject, $eventData['scope'], $eventData['event'], $data);
			}

			return $return;
		}

		/**
		 * @brief set available plugins that are accepting events
		 */
		public function setAvailablePlugins() {
			if(!empty($this->__availablePlugins)) {
				return false;
			}
			
			$_this = EventCore::getInstance();
			$_this->__availablePlugins = array_values(ClassRegistry::init('Installer.Plugin')->getActiveInstalledPlugins());
		}


		/**
		 * @brief Get a list of plugins that will be affected by running an event
		 *
		 * This will return all plugins that have an event class but sometimes if
		 * you need to run a global trigger but want to do it one at a time this
		 * list will help out.
		 * 
		 * @param string $eventName the name of the event to run
		 * @access public
		 *
		 * @return array the array of plugins that will be run
		 */
		public function pluginsWith($eventName){
			$_this =& EventCore::getInstance();
			if(!isset($_this->_eventHandlerCache[$eventName])){
				return array();
			}

			$return = array();
			foreach($_this->_eventHandlerCache[$eventName] as $plugin){
				$return[] = $_this->_extractPluginName($plugin);
			}

			return $return;
		}

		/**
		 * @brief dynamically turn plugins on during a request.
		 *
		 * This can be used to turn a plugin on programatically.
		 *
		 * @param mixed $plugins single / list of plugins to turn on
		 * @param bool $allowUninstalled allow turning on a plugin that is not installed (not recommended)
		 *
		 * @return bool true if they were added, false if not
		 */
		public function activatePlugins($plugins = array(), $allowUninstalled = false) {
			if(!is_array($plugins)) {
				$plugins = array($plugins);
			}

			if(empty($plugins)) {
				return false;
			}

			if($allowUninstalled) {
				$this->__installedPlugins = array_values(ClassRegistry::init('Installer.Plugin')->getAllPlugins());
			}
			else {
				$this->__installedPlugins = array_values(ClassRegistry::init('Installer.Plugin')->getInstalledPlugins());
			}
			
			foreach($plugins as $plugin) {
				if(in_array($plugin, $this->__installedPlugins) && !in_array($plugin, $this->__availablePlugins)) {
					$_this->__availablePlugins[] = $plugin;
				}
			}

			return true;
		}

		/**
		 * @brief check if a plugin is active for the current request
		 *
		 * This method is used within the core to stop people accessing the controllers
		 * and actions directly. It can also be used to see if plugins are active
		 * for the request. Even if something is installed and active, it may have
		 * been dynamically turned off (or the other way round)
		 *
		 * @access public
		 *
		 * @param string $plugin the name of the plugin to check
		 * 
		 * @return bool true if its active, false if not
		 */
		public function isPluginActive($plugin) {
			if(!$plugin || !in_array($plugin, $this->__availablePlugins)) {
				return false;
			}

			return true;
		}

		/**
		 * Loads all available event handler classes for enabled plugins
		 *
		 */
		protected function _loadEventHandlers(){
			$this->_eventHandlerCache = Cache::read('event_handlers', 'core');

			if(empty($this->_eventHandlerCache)) {
				$plugins = App::objects('plugin');
				foreach((array)$plugins as $pluginName){
					$filename = App::pluginPath($pluginName) . Inflector::underscore($pluginName) . '_events.php';
					$className = Inflector::camelize($pluginName . '_events');
					if(file_exists($filename)){
						if(EventCore::_loadEventClass($className, $filename)) {
							EventCore::_getAvailableHandlers($this->_eventClasses[$className]);
						}
					}
				}

				Cache::write('event_handlers', $this->_eventHandlerCache, 'core');
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
		static protected function _dispatchEvent(&$HandlerObject, $scope, $eventName, $data = array()){
			$eventHandlerMethod = EventCore::_handlerMethodName($eventName);
			$_this =& EventCore::getInstance();

			$return = array();

			if(isset($_this->_eventHandlerCache[$eventName])){
				foreach($_this->_eventHandlerCache[$eventName] as $eventClass){
					$pluginName = EventCore::_extractPluginName($eventClass);
					if(!empty($_this->__availablePlugins) && !in_array(Inflector::camelize($pluginName), $_this->__availablePlugins)) {
						continue;
					}

					if(($scope == 'Global' || $scope == $pluginName)){
						EventCore::_loadEventClass($eventClass);
						$Event = new Event($eventName, $HandlerObject, $pluginName);

						$return[$pluginName] = call_user_func_array(array($_this->_eventClasses[$eventClass], $eventHandlerMethod), array($Event, $data));
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
		protected function _parseEventName($eventName){
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
		protected function _handlerMethodName($eventName){
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
		protected function _getAvailableHandlers($Event){
			if(is_object($Event)){
				$_this =& EventCore::getInstance();

				$reflection = new ReflectionClass($Event);
				$classMethods = array_filter($reflection->getMethods(), create_function('$v', 'return $v->class == "'.get_class($Event).'" && substr($v->name, 0, 2) == "on";'));
				$handlers = array_map(create_function('$v', 'return lcfirst(substr($v->name, 2));'), $classMethods);

				foreach($handlers as $handlerName){
						$_this->_eventHandlerCache[$handlerName][] = get_class($Event);
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
		protected function _loadEventClass($className, $filename = false){
			$_this =& EventCore::getInstance();
			if(isset($_this->_eventClasses[$className]) && is_object($_this->_eventClasses[$className])) {
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
				$_this->_eventClasses[$className] =& new $className();
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
		protected function _extractPluginName($className){
			$_this =& EventCore::getInstance();
			if(!isset($_this->pluginNameCache->{$className})){
				$_this->pluginNameCache->{$className} = Inflector::underscore(substr($className, 0, strlen($className) - 6));
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