<?php
	/**
	 * AnalogueHelper
	 * Maps helpers to other helper names
	 * @author Joe Beeson <jbeeson@gmail.com>
	 *
	 * var $helpers = array('Analogue' => array('html' => 'mine'));
	 */
	class AnalogueHelper extends AppHelper {

		/**
		 * Settings
		 * @var array
		 * @access private
		 */
		private $settings = array();

		/**
		 * Executed prior to rendering
		 * @return null
		 * @access public
		 */
		public function beforeRender() {
			$view = ClassRegistry::getObject('view');

			// Map the helper against our link
			foreach ($this->settings as $link=>$helper) {
				$link 	= strtolower($link);
				$helper = strtolower($helper);
				$view->loaded[$link] = $view->loaded[$helper];
			}
		}

		/**
		 * Construction
		 * @param array $settings
		 * @return null
		 * @access public
		 */
		public function __construct($settings = array()) {
			$this->settings = am(
				$this->settings,
				$settings
			);
		}

	}
?>