<?php
	class InfinitasExceptionRenderer extends ExceptionRenderer {
		/**
		 * @brief overload the template when needed
		 * 
		 * If the exception is extended from InfinitasExceptions there is a custom
		 * template available for the exception it is set here
		 * 
		 * @param Exception $exception 
		 */
		public function __construct(Exception $exception) {
			parent::__construct($exception);
			
			if($exception instanceof InfinitasException) {
				$this->__changeTemplate(get_class($exception), $exception->plugin());
			}
		}
		
		/**
		 * @brief set the layout based on the user type 
		 */
		public function render() {
			$this->controller->layout = 'error';
			
			if(isset($this->controller->request['params']['admin']) && $this->controller->request['params']['admin']) {
				$this->controller->layout = 'admin_error';
			}
			
			$this->controller->viewClass = 'Libs.Infinitas';
			
			parent::render();
		}

		/**
		 * Get a custom InfinitasErrorController instance
		 * 
		 * This instance tries to load up the very minimum to display the error
		 * page. If anything goes wrong it falls back to the default CakeErrorController
		 *
		 * @param Exception $exception The exception to get a controller for.
		 * 
		 * @return Controller
		 */
		protected function _getController($exception) {
			App::uses('InfinitasErrorController', 'Controller');
			if (!$request = Router::getRequest(true)) {
				$request = new CakeRequest();
			}
			
			$response = new CakeResponse(array('charset' => Configure::read('App.encoding')));
			try {
				return new InfinitasErrorController($request, $response);
			} 
			
			catch (Exception $e) {
				return parent::_getController($exception);
			}
		}
		
		/**
		 * @brief change the template if there is one available
		 * 
		 * If a template is found for the current exception then it is set 
		 * 
		 * @param string $exceptionClass The name of the exception class
		 * @param string $plugin The plugin the exception belongs to
		 */
		private function __changeTemplate($exceptionClass, $plugin) {
			$view = Inflector::underscore(str_replace(array('Exception', $plugin), '', $exceptionClass));
			try {
				$path = InfinitasPlugin::path($plugin) . 'View' . DS . 'Errors' . DS . $view . '.ctp';
				
				if(is_file($path)) {
					$this->template = sprintf('%s.%s', $plugin, $view);
				}
			}
			
			catch(Exception $e) {}
			
			$path = APP . 'View' . DS . 'Errors' . DS . $view . '.ctp';
			if(is_file($path)) {
				$this->template = $view;
			}
		}
	}
