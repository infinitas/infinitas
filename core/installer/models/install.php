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
		public $name = 'Install';
		public $useTable = false;
		public $useDbConfig = false;

		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'driver' => array(
					'rule' => 'notempty',
					'message' => __('You need to select a database engine', true)
				),
				'database' => array(
					'rule' => 'notempty',
					'message' => __('You need to enter a database name', true)
				),
				'login' => array(
					'rule' => 'notempty',
					'message' => __('You need to enter the username used to connect to the database', true)
				),
				'host' => array(
					'rule' => 'notempty',
					'message' => __('You need to enter the host for your database', true)
				),
			);
		}

/**
 * Ucky hacks to make the model work without a datasource
 */
		public $_schema = array();
		public function find() {
			return true;
		}
	}