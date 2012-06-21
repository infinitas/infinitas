<?php
	class DatabasesController extends ServerStatusAppController {
		public $uses = array();

		public function admin_dashboard() {
			
		}

		public function admin_mysql() {
			$User = ClassRegistry::init('Users.User');
			$globalVars = $User->query('show global variables');
			$globalVars = array_combine(
				Set::extract('/VARIABLES/Variable_name', $globalVars),
				Set::extract('/VARIABLES/Value', $globalVars)
			);

			$localVars = $User->query('show variables');
			$localVars = array_combine(
				Set::extract('/VARIABLES/Variable_name', $localVars),
				Set::extract('/VARIABLES/Value', $localVars)
			);

			$localVars = Set::diff($localVars, $globalVars);

			$this->set(compact('globalVars', 'localVars'));
		}
	}