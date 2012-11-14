<?php
/**
 * ModulePosition
 *
 * @package Infinitas.Modules.Model
 */

/**
 * ModulePosition
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Modules.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ModulePosition extends ModulesAppModel {
/**
 * HasMany relations
 *
 * @var array
 */
	public $hasMany = array(
		'Module' => array(
			'className' => 'Modules.Module',
			'foreignKey' => 'position_id',
		)
	);

/**
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
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
 * check if the passed in position is valid
 *
 * @param string $position the name of a position
 *
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