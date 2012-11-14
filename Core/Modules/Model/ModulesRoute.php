<?php
/**
 * ModulesRoute
 *
 * @package Infinitas.Modules.Model
 */

/**
 * ModulesRoute
 *
 * The join between modules and routes which allows loading specific modules
 * on specific routes
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Modules.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ModulesRoute extends ModulesAppModel {
/**
 * belongs to relations
 *
 * @var array
 */
	public $belongsTo = array(
		'Modules.Module',
		'Routes.Route'
	);

/**
 * overload __construct for translateable validation
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