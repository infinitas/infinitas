<?php
/**
 * Install
 *
 * @package Infinitas.Installer.Model
 */

App::uses('Model', 'Model');

/**
 * Install
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Installer.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */


class Install extends Model {
/**
 * this model does not use a table
 *
 * @var boolean
 */
	public $useTable = false;

/**
 * no behaviors
 *
 * @var boolean
 */
	public $actsAs = false;

/**
 * overload construct for translated validation
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
 * merge defaults before validation
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

/**
 * Disable Finds
 *
 * @return boolean
 */
	public function find() {
		return true;
	}

}