<?php
class ModulePosition extends ModulesAppModel {
	public $hasMany = array(
		'Module' => array(
			'className' => 'Modules.Module',
			'foreignKey' => 'position_id',
		)
	);

/**
 * @brief overload __construct to create validation messages that are translateable
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 */
	public function  __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'name' => array(
				'validName' => array(
					'required' => true,
					'rule' => '/^[a-z0-9_]{1,50}$/',
					'message' => __d('modules', 'Please enter a valid name, lowercase letters, numbers and underscores only')
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('modules', 'There is already a position with that name')
				)
			)
		);
	}

/**
 * @brief check if the passed in position is valid
 *
 * @param string $position the name of a position
 * @return boolean
 */
	public function isPosition($position) {
		return (bool)$this->find(
			'count',
			array(
				'conditions' => array(
					$this->alias . '.' . $this->displayField => $position
				)
			)
		);
	}
}