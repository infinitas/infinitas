<?php
	class InfinitasException extends RuntimeException {

		/**
		 * Array of attributes that are passed in from the constructor, and
		 * made available in the view when a development error is displayed.
		 *
		 * @var array
		 */
		protected $_attributes = array();

		/**
		 * Template string that has attributes sprintf()'ed into it.
		 *
		 * @var string
		 */
		protected $_messageTemplate = '';
	
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
			if($this instanceof InfinitasException) {
				$this->_plugin = str_replace('Exception', '', get_parent_class($this));
			}
			
			if (is_array($message)) {
				$this->_attributes = $message;
				$message = __d($this->_plugin ? $this->_plugin : 'infinitas', $this->_messageTemplate, $message);
			}
			
			parent::__construct($message, $code);
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

		/**
		 * Get the passed in attributes
		 *
		 * @return array
		 */
		public function getAttributes() {
			return $this->_attributes;
		}
	}
