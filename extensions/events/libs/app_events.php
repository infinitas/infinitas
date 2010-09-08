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
		final public function testEvent(){
			echo '<h1>your event is working</h1>';
			echo '<p>The following events are available for use in the Infinitas core</p>';
			pr($this->availableEvents());
			exit;
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
		 * Slug urls.
		 *
		 * Use this method to figure out what vars are needed for the
		 * controller / action pair you need to get to. You  will get some data,
		 * normally in the form of Model::find(first) that you can use.
		 *
		 */
		public function onSlugUrl(&$event, $data = null){}

		/**
		 * Load config vars from the db.
		 *
		 * This gets all the config vars from the database and loads them in to the
		 * {#see Configure} class to be used later in the app
		 *
		 * Called in InfinitasComponent::initialize
		 *
		 * @return true
		 */
		public function onSetupConfig(&$event, $data = null){}

		public function onSetupConfigStart(&$event, $data = null){}
		
		#public function onSetupConfigEnd(&$event, $data = null){}

		#public function onSetupThemeStart(&$event, $data = null){}

		#public function onSetupThemeSelector(&$event, $data = null){}

		#public function onSetupThemeEnd(&$event, $data = null){}

		#public function onFindBrowser(&$event, $data = null){}

		#public function onFindOperatingSystem(&$event, $data = null){}

		/**
		 * Adding routes
		 *
		 * Add routing to for the app from your plugin by calling Router::connect
		 *
		 * called in routes.php
		 * 
		 * @return nothing, it wont do anything
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
		 * called in AppController::beforeRender()
		 *
		 * @return mixed string | array like you would use in Controller->helpers
		 */
		public function onRequireHelpersToLoad(&$event = null){
			return array();
		}

		/**
		 * Get js files to include
		 *
		 * Allows you to include javascript from your plugin that can be loaded
		 * on any page
		 * 
		 * called in AppController::beforeRender()
		 *
		 * @param $event some data for the current event
		 * @return mixed string | array() of javascript like HtmlHelper::script() takes
		 */
		public function onRequireJavascriptToLoad(&$event, $data = null){}

		/**
		 * Get vcss files to include
		 *
		 * Allows you to include css from your plugin that can be loaded
		 * on any page
		 *
		 * called in AppController::beforeRender()
		 *
		 * @param $event some data for the current event
		 * @return mixed string | array() of css like HtmlHelper::css() takes
		 */
		public function onRequireCssToLoad(&$event, $data = null){}

		/**
		 * Load components
		 *
		 * Allows you to include components into the app that can be accessed
		 * globaly.
		 *
		 * called before AppController::__construct()
		 *
		 * @param $event some data for the current event
		 * @return mixed string | array() of css like HtmlHelper::css() takes
		 */
		public function onRequireComponentsToLoad(&$event = null){
			return array();
		}

		/**
		 *
		 */
		public function onAttachBehaviors(&$event = null){}

		/**
		 * Require Global templates used in page rendering.
		 *
		 * This is called just before a page is rendered by the InfinitasView
		 * and takes an array of items to add. Its used for adding global pieces
		 * to your template.
		 *
		 * if you pass back array('phone' => '012 345 6789') from foo plugin
		 * you can use {{templates.foo.phone}} in your views to show your phone
		 * number.
		 */
		public function onRequireGlobalTemplates(&$event){}
	}