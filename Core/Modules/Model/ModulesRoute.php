<?php
	class ModulesRoute extends ModulesAppModel {
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'module_id' => array(
					'validateRecordExists' => array(
						'required' => true,
						'rule' => 'validateRecordExists',
						'message' => __d('modules', 'The selected module is not valid'))),
				'route_id' => array(
					'validateRecordExists' => array(
						'required' => true,
						'rule' => 'validateRecordExists',
						'message' => __d('modules', 'The selected route is not valid')
					)));
		}
	}