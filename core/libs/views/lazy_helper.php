<?php

	/**
	 * AutoHelperView
	 *
	 * Provides automatic loading, or "lazy loading" of heleprs for the `View`
	 * class.
	 *
	 * If a helper needs to be called prior to rendering or if it has any
	 * settings you should keep it in your controller's `$helpers` array.
	 *
	 * @author Joe Beeson <jbeeson@gmail.com>
	 */
	class LazyHelperView extends ThemeView {

		/**
		 * Stores our array of known helpers.
		 *
		 * @var array
		 * @access protected
		 */
		protected $_helpers = array();

		/**
		 * lazy Helpers
		 */
		protected $_lazyHelpers = array();

		private $__helperMap = array();

		public function __construct(&$controller, $register = true) {
			parent::__construct($controller, $register);
			$this->__registerHelpers();
		}

		/**
		 * Called when a request for a non-existant member variable is caught.
		 * If the requested $variable matches a known helper we will attempt to
		 * load it up for the caller.
		 *
		 * @param string $variable
		 * @return mixed
		 * @access public
		 */
		public function __get($variable = '') {
			// Is the $variable a known helper name? If so, load the helper
			$this->_getHelpers();
			if(isset($this->__helperMap[$variable])){
				$this->_loadHelper($variable, $this->__helperMap[$variable]);
			}
			else if (in_array($variable, $this->_getHelpers())) {
				pr($variable);
				exit;
				$this->_loadHelper($variable);
			}

			// Make sure the variable is now available and if so, return it
			if (isset($this->$variable)) {
				return $this->$variable;
			}
		}

		/**
		 * Returns an array of known helpers. We will cache the known helpers
		 * so that we don't have to keep bothering App::object()
		 *
		 * @param boolean $cache
		 * @return array
		 * @access protected
		 */
		protected function _getHelpers($cache = true) {
			// Check if we don't have the array of if we're told not to cache
			if (empty($this->_helpers) or !$cache) {
				$this->_helpers = App::objects('helper');
			}
			$this->_helpers = array_merge($this->helpers, $this->_helpers, $this->_lazyHelpers);

			$this->__mapHelpers($this->_helpers);


			// Return the array of helpers
			return $this->_helpers;

		}

		/**
		 * Convenience method for loading up a specific helper.
		 *
		 * @param string $helper
		 * @return null
		 * @access protected
		 */
		protected function _loadHelper($helper, $class) {
			// Load the variable up
			$this->loaded = $this->_loadHelpers(
				$this->loaded,
				array($class)
			);

			// Assign the helper into a member variable
			$this->$helper = $this->loaded[$helper];

		}

		/**
		 * Renders and returns output for given view filename with its
		 * array of data.
		 *
		 * We override the `View` method because the core uses a pass-by-ref in
		 * its code, which causes our `__get` method to barf, everywhere.
		 *
		 * @param string $___viewFn
		 * @param array $___dataForView
		 * @param boolean $loadHelpers
		 * @param boolean $cached
		 * @return string
		 * @access public
		 */
		public function _render($___viewFn, $___dataForView, $loadHelpers = true, $cached = false) {
			$loadedHelpers = array();
			if ($this->helpers != false && $loadHelpers === true) {
				$loadedHelpers = $this->_loadHelpers($loadedHelpers, $this->helpers);
				$helpers = array_keys($loadedHelpers);
				$helperNames = array_map(array('Inflector', 'variable'), $helpers);

				for ($i = count($helpers) - 1; $i >= 0; $i--) {
					$name = $helperNames[$i];
					$helper =& $loadedHelpers[$helpers[$i]];

					if (!isset($___dataForView[$name])) {
						${$name} =& $helper;
					}
					$this->loaded[$helperNames[$i]] =& $helper;
					$this->{$helpers[$i]} = $helper;
				}
				$this->_triggerHelpers('beforeRender');
				unset($name, $loadedHelpers, $helpers, $i, $helperNames, $helper);
			}

			extract($___dataForView, EXTR_SKIP);
			ob_start();

			if (Configure::read() > 0) {
				include ($___viewFn);
			} else {
				@include ($___viewFn);
			}

			if ($loadHelpers === true) {
				$this->_triggerHelpers('afterRender');
			}

			$out = ob_get_clean();
			$caching = (
				isset($this->loaded['cache']) &&
				$this->cacheAction != false &&
				Configure::read('Cache.check') === true
			);

			if ($caching) {
				if (is_a($this->loaded['cache'], 'CacheHelper')) {
					$cache =& $this->loaded['cache'];
					$cache->base = $this->base;
					$cache->here = $this->here;
					$cache->helpers = $this->helpers;
					$cache->action = $this->action;
					$cache->controllerName = $this->name;
					$cache->layout = $this->layout;
					$cache->cacheAction = $this->cacheAction;
					$cache->cache($___viewFn, $out, $cached);
				}
			}
			return $out;
		}

		/**
		 * triggers an event to get all the helpers.
		 *
		 * @return bool if helpers are set;
		 */
		private function __registerHelpers(){
			$data = EventCore::trigger($this, 'requireHelpersToLoad');

			if(isset($data['requireHelpersToLoad']['libs'])){
				$libs['libs'] = $data['requireHelpersToLoad']['libs'];
				$data['requireHelpersToLoad'] = $libs + $data['requireHelpersToLoad'];
			}

			foreach($data['requireHelpersToLoad'] as $plugin => $helpers){
				if(!is_array($helpers)){
					$helpers = array($helpers);
				}
				$helpers = array_filter($helpers);
				if(empty($helpers)){
					continue;
				}
				if(strstr($plugin, '.')){
					$this->_lazyHelpers[$plugin] = $helpers;
					continue;
				}
				foreach($helpers as $helper => $config){
					if(is_string($config) && is_int($helper)){
						// be lazy
						$this->_lazyHelpers[] = $config;
					}
					// need to load
					else if($config === true || (is_array($config) && !empty($config))){
						$this->helpers[$helper] = $config;
					}
					else{
						$this->_lazyHelpers[$helper] = $config;
					}
				}
			}

			return !empty($this->_lazyHelpers);
		}

		private function __mapHelpers($helpers){
			if(empty($helpers)){
				return true;
			}

			foreach($helpers as $id => $helper){
				if(is_int($id)){
					$parts = explode('.', $helper);
				}
				else{
					$parts = explode('.', $id);
					$helper = $id;
				}

				if(count($parts) == 1){
					$this->__helperMap[$helper] = $helper;
				}
				else{
					$this->__helperMap[$parts[1]] = $helper;
				}
			}
		}

	}