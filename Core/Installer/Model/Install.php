<?php
/**
 * Comment Template.
 *
 * @todo Implement .this needs to be sorted out.
 *
 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 * @filesource
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org
 * @package sort
 * @subpackage sort.comments
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 */

App::uses('Model', 'Model');

class Install extends Model {
/**
 * @brief this model does not use a table
 * 
 * @var boolean
 */
	public $useTable = false;

/**
 * @brief no behaviors 
 * 
 * @var boolean
 */
	public $actsAs = false;

/**
 * @brief overload construct for translated validation
 * 
 * @param boolean $id    [description]
 * @param [type]  $table [description]
 * @param [type]  $ds    [description]
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		$this->_schema = array();

		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'datasource' => array(
				'rule' => 'notempty',
				'message' => __d('installer', 'You need to select a database engine')
			),
			'database' => array(
				'rule' => 'notempty',
				'message' => __d('installer', 'You need to enter a database name')
			),
			'login' => array(
				'rule' => 'notempty',
				'message' => __d('installer', 'You need to enter the username used to connect to the database')
			),
			'host' => array(
				'rule' => 'notempty',
				'message' => __d('installer', 'You need to enter the host for your database')
			),
		);
	}

/**
 * @brief merge defaults before validation
 * 
 * @return void
 */
	public function beforeValidate() {
		$this->data[$this->alias] = array_merge(
			array('datasource' => null, 'host' => null, 'login' => null, 'password' => null, 'database' => null, 'port' => null, 'prefix' => null),
			array_filter($this->data[$this->alias])
		);

		$this->data[$this->alias]['port'] = !empty($this->data[$this->alias]['port']) ? $this->data[$this->alias]['port'] : null;
		$this->data[$this->alias]['prefix'] = !empty($this->data[$this->alias]['prefix']) ? $this->data[$this->alias]['prefix'] : null;
	}

	public function find() {
		return true;
	}
	
}