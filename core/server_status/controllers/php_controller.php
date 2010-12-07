<?php
	class PhpController extends ServerStatusAppController{
		public $name = 'Php';

		public $uses = array();

		/**
		 * @brief Status pannel for apc
		 */
		public function admin_apc(){
			// Configure::write('debug', 0);
			$this->layout = 'ajax';
		}

		public function admin_info(){

		}
	}