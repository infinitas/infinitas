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

	class Install extends Model {
		public $useTable = false;

		public $useDbConfig = false;
		
		public $actsAs = false;

		public function __construct($id = false, $table = null, $ds = null) {
			$this->_schema = array();

			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'driver' => array(
					'rule' => 'notempty',
					'message' => __('You need to select a database engine')
				),
				'database' => array(
					'rule' => 'notempty',
					'message' => __('You need to enter a database name')
				),
				'login' => array(
					'rule' => 'notempty',
					'message' => __('You need to enter the username used to connect to the database')
				),
				'host' => array(
					'rule' => 'notempty',
					'message' => __('You need to enter the host for your database')
				),
			);
		}
		
		public function beforeValidate() {
			$this->data[$this->alias] = array_merge(
				array('driver' => null, 'host' => null, 'login' => null, 'password' => null, 'database' => null, 'port' => null, 'prefix' => null),
				array_filter($this->data[$this->alias])
			);
			
			$this->data[$this->alias]['port'] = !empty($this->data[$this->alias]['port']) ? $this->data[$this->alias]['port'] : null;
			$this->data[$this->alias]['prefix'] = !empty($this->data[$this->alias]['prefix']) ? $this->data[$this->alias]['prefix'] : null;
		}

		public function find() {
			return true;
		}
	}