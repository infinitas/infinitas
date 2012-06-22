<?php
class ModulesRoute extends ModulesAppModel {
/**
 * @brief belongs to relations
 *
 * @var array
 */
	public $belongsTo = array(
		'Modules.Module',
		'Routes.Route'
	);

/**
 * @brief overload __construct for translateable validation
 * 
 * @param type $id
 * @param type $table
 * @param type $ds
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'module_id' => array(
				'notEmpty' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => __d('modules', 'A module is required')),
				'validateRecordExists' => array(
					'rule' => 'validateRecordExists',
					'message' => __d('modules', 'The selected module is not valid'))),
			'route_id' => array(
				'notEmpty' => array(
					'required' => true,
					'rule' => 'notEmpty',
					'message' => __d('modules', 'A route is required')),
				'validateRecordExists' => array(
					'rule' => 'validateRecordExists',
					'message' => __d('modules', 'The selected route is not valid')
				)));
	}
}