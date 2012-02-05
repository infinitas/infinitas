<?php
	class PhpController extends ServerStatusAppController {
		public $name = 'Php';

		public $uses = array();

		/**
		 * @brief Status pannel for apc
		 */
		public function admin_apc() {
			// Configure::write('debug', 0);
			if(!function_exists('apc_cache_info')) {
				$this->notice(
					__('APC is not installed, or has been deactivated', true),
					array(
						'level' => 'warning',
						'redirect' => true
					)
				);
			}
			
			$this->layout = 'ajax';
		}

		public function admin_info() {

		}
	}