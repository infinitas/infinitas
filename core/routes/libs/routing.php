<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.routes
	 * @subpackage Infinitas.routes.events
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class InfinitasRouting extends Object{
		public function setup(){
			InfinitasRouting::__registerExtensions();
			InfinitasRouting::__buildRoutes();
		}

		/**
		 * Build routes for the app.
		 *
		 * Allows other plugins to register routes to be used in the app and builds
		 * the routes from the database.
		 */
		private function __buildRoutes(){
			EventCore::trigger(new StdClass(), 'setupRoutes');

			//TODO: Figure out cleaner way of doing this. If infinitas is not installed, this throws a datasource error.
			$databaseConfig = APP.'config'.DS.'database.php';
			if(file_exists($databaseConfig) && filesize($databaseConfig) > 0) {
				$routes = Classregistry::init('Routes.Route')->getRoutes();
				if (!empty($routes)) {
					foreach($routes as $route ){
						if (false) {
							debugRoute($route);
							continue;
						}

						call_user_func_array(array('Router', 'connect'), $route['Route']);
					}
				}
			}
		}

		/**
		 * Register extensions that are used in the app
		 *
		 * Call all plugins and see what extensions are need, this is cached
		 */
		private function __registerExtensions(){
			$extensions = Cache::read('extensions', 'routes');
			if($extensions === false){
				$extensions = EventCore::trigger(new StdClass(), 'setupExtensions');

				$_extensions = array();
				foreach($extensions['setupExtensions'] as $plugin => $ext){
					$_extensions = array_merge($_extensions, (array)$ext);
				}

				$extensions = array_flip(array_flip($_extensions));
				Cache::write('extentions', $extensions, 'routes');
				unset($_extensions);
			}
			
			call_user_func_array(array('Router', 'parseExtensions'), $extensions);
		}
	 }