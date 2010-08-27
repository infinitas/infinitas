<?php
	/**
	 * Base class for events.
	 * 
	 * This is the base class for events that can provide some documentation about
	 * what events are available and what they do. It extends Object so you can
	 * do things like $this->log(), redirect() and other basic methods
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package infinitas.events
	 * @subpackage infinitas.events.app_events
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class AppEvents extends Object{
		/**
		 * internal list of available methods
		 */
		private $__events = array();

		/**
		 * builds a list of available events
		 */
		public function  __construct() {
			if(!empty($this->__events)){
				return true;
			}
			
			$this->__events = get_class_methods('AppEvents');
			foreach($this->__events as $k => $event){
				if(substr($event, 0, 2) != 'on'){
					unset($this->__events[$k]);
				}
			}

			sort($this->__events);
		}

		/**
		 * available methods.
		 *
		 * returns an array of all the available events.
		 */
		final public function availableEvents(){
			return $this->__events;
		}
		/**
		 * test if things are working
		 */
		final public function onTestEvent(&$event){
			echo 'your event is working';
			pr($event);
		}

		/**
		 * Load the default cache settings.
		 *
		 * allows all the parts of the app to set up any cache configs that are
		 * needed.
		 *
		 * Called in InfinitasComponent::initialize
		 *
		 * @return true
		 */
		public function onSetupCache(&$event, $data = null){}

		/**
		 * 
		 */
		public function onSlugUrl(&$event, $data = null){}

		public function onSetupConfigStart(&$event, $data = null){}
		/**
		 * Load config vars from the db.
		 *
		 * This gets all the config vars from the database and loads them in to the
		 * {#see Configure} class to be used later in the app
		 *
		 * Called in InfinitasComponent::initialize
		 *
		 * @todo load the users configs also.
		 *
		 * @return true
		 */
		public function onSetupConfig(&$event, $data = null){}
		
		#public function onSetupConfigEnd(&$event, $data = null){}

		#public function onSetupThemeStart(&$event, $data = null){}

		#public function onSetupThemeSelector(&$event, $data = null){}

		#public function onSetupThemeEnd(&$event, $data = null){}

		#public function onFindBrowser(&$event, $data = null){}

		#public function onFindOperatingSystem(&$event, $data = null){}

		/**
		 * 
		 */
		public function onSetupRoutes(&$event, $data = null){}

		#public function onSetupThemeLayout(&$event, $data = null){}

		#public function onUserLogin(&$event, $data = null){}

		#public function onUserRegistration(&$event, $data = null){}

		#public function onUserActivation(&$event, $data = null){}

		/**
		 * Load helpers.
		 *
		 * This event provides a way for you to attach your helper to the entire
		 * application without needin to modyfy app_controller.php
		 *
		 * just return an array like you would use in Controller->helpers
		 */
		public function onRequireHelpersToLoad(&$event = null){
			return array();
		}

		/**
		 *
		 */
		public function onAttachBehaviors(&$event = null){}
	}