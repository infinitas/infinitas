<?php
	class InfinitasException extends CakeException {
		/**
		 * @brief the name of the plugin that raised the exception
		 * 
		 * @var string
		 */
		protected $_plugin = '';
		
		/**
		 * @brief set up the plugin name 
		 * 
		 * This is used to give the plugin a chance to handle its own exceptions
		 * 
		 * @param type $message
		 * @param type $code 
		 */
		public function __construct($message, $code = null) {
			parent::__construct($message, $code);
			
			if($this instanceof InfinitasException) {
				$this->_plugin = str_replace('Exception', '', get_parent_class($this));
			}
		}
		
		/**
		 * @brief get the plugin name
		 * 
		 * The plugin name is used by the InfinitasExceptionRenderer to check for
		 * custom plugin error pages
		 * 
		 * @return string
		 */
		public function plugin() {
			return $this->_plugin;
		}
	}
