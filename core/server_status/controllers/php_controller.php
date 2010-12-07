<?php
	class PhpController extends ServerStatusAppController{
		public $name = 'Php';

		public $uses = array();

		/**
		 * @brief Status pannel for apc
		 */
		public function admin_apc(){
			// Configure::write('debug', 0);
			if(Configure::read('Cache.engine') != 'APC'){
				$this->notice(
					__('APC does not seem to be configured', true),
					array(
						'level' => 'warning',
						'redirect' => true
					)
				);
			}
			
			$this->layout = 'ajax';
		}

		public function admin_info(){

		}
	}