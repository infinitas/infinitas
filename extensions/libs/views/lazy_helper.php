<?php
	/**
	 * AutoHelperView
	 * Provides automatic helper loading for views.
	 *
	 * @author Joe Beeson <jbeeson@gmail.com>
	 */
	class LazyHelperView extends View {

		/**
		 * Stores our array of known helpers.
		 *
		 * @var array
		 * @access protected
		 */
		protected $_helpers = array();

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
			if (in_array($variable, $this->_getHelpers())) {
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
		protected function _loadHelper($helper) {

			// Load the variable up
			$this->loaded = $this->_loadHelpers(
				$this->loaded,
				array($helper)
			);

			// Assign the helper into a member variable
			$this->$helper = $this->loaded[$helper];

		}

		/**
		 * Renders and returns output for given view filename with its
		 * array of data. We override the View classes _render method because it
		 * makes use of "pass by ref" which goes to hell with our __get function
		 *
		 * @param string $___viewFn Filename of the view
		 * @param array $___dataForView Data to include in rendered view
		 * @param boolean $loadHelpers Boolean to indicate that helpers should be loaded.
		 * @param boolean $cached Whether or not to trigger the creation of a cache file.
		 * @return string Rendered output
		 * @access protected (but actually public)
		*/
		public function _render($___viewFn, $___dataForView, $loadHelpers = true, $cached = false) {
			$loadedHelpers = array();

			/**
			 * We still let the _render method load up any helpers that are
			 * explicitly asked for, this is for any helpers that may have the
			 * beforeRender callback implemented.
			 */
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
					$this->loaded[$helperNames[$i]] = $helper;
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
				(($this->cacheAction != false)) && (Configure::read('Cache.check') === true)
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
	}