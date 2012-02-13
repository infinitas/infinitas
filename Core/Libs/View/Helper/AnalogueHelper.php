<?php
	/**
	 * AnalogueHelper
	 * Renames, or "maps", helpers prior to rendering
	 * @author Joe Beeson <jbeeson@gmail.com>
	 *
	 * Example
	 *
	 * public $helpers = array(
	 *   'Analogue' => array(
	 *	 array(
	 *	   'helper' => 'MyHtml',
	 *	   'rename' => 'Html'
	 *	 ),
	 *	 array(
	 *	   'helper' => 'MyForm',
	 *	   'rename' => 'Form'
	 *	 )
	 *   )
	 * );
	 */
	class AnalogueHelper extends AppHelper {

		/**
		 * Mappings
		 * 
		 * @var array
		 * @access private
		 */
		private $__mappings = array();

		/**
		 * View
		 * 
		 * @var __view
		 * @access private
		 */
		private $__view;

		/**
		 * Executed prior to rendering
		 *
		 * @return null
		 * @access public
		 */
		public function beforeRender() {

			// Loop through and call _mapHelper()
			foreach ($this->__mappings as $mapping) {
				$this->_mapHelper($mapping);
			}
		}

		/**
		 * Construction
		 * @param array $settings
		 * @return null
		 * @access public
		 */
		public function __construct($mappings = array()) {

			// Grab our View object for use later...
			$this->__view = ClassRegistry::getObject('view');

			// Merge our mappings together...
			$this->__mappings = am(
				$this->__mappings,
				$mappings
			);
		}

		/**
		 * Performs the mapping of a helper object to a new name
		 * @param array $mapping
		 * @return null
		 * @access private
		 */
		private function _mapHelper($mapping = array()) {

			// Helpers are always lowercased in the View object
			$mapping = array_map('strtolower', $mapping);

			// Extract our array for use
			extract($mapping);

			// Only continue if we have a valid, loaded helper
			if (isset($helper)) {
				if (isset($this->__view->loaded[$helper]) and isset($rename)) {
					$this->__view->loaded[$rename] = $this->__view->loaded[$helper];
				}
			}
		}

	}