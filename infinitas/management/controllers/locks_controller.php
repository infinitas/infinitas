<?php
	/**
	 * Controller to manage reckord locking.
	 *
	 * This controller will unlock records that are locked.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package management
	 * @subpackage management.controllers.locks
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class LocksController extends ManagementAppController{
		var $name = 'Locks';

		var $uses = array();

		function beforeFilter(){
			parent::beforeFilter();
			$this->db = ConnectionManager::getDataSource('default');
		}

		function admin_index(){
			$lockableTables = $this->_getLockableTables();

			foreach($lockableTables as $table ){
				$count = $this->db->query(
					'SELECT COUNT(*) AS `count` FROM `'.$table['table'].'` AS `'.$table['model'].'`   WHERE `'.$table['model'].'`.`locked` = 1'
				);

				$locks[] = array(
					'plugin' => $table['plugin'],
					'model'  => $table['model'],
					'table'  => $table['table'],
					'locked' => $count[0][0]['count']
				);
			}

			$this->set('locks', $locks);
		}

		function admin_unlock(){

		}

		function _getTables(){
			$tables = $this->db->query('SHOW TABLES;');
			return Set::extract('/TABLE_NAMES/Tables_in_infinitas', $tables);
		}

		function _getLockableTables(){
			$tableNames = $this->_getTables();
			$lockableTables = array();

			foreach($tableNames as $table ){
				$fields = $this->db->query('DESCRIBE '.$table);
				$fields = Set::extract('/COLUMNS/Field', $fields);

				if (in_array('locked', $fields)) {
					$_table = explode('_', $table, 2);
					$lockableTables[] = array(
						'plugin' => ucfirst($_table[0]),
						'model'  => Inflector::classify($_table[1]),
						'table'  => $table
					);
				}
			}

			return $lockableTables;
		}

		function admin_mass(){

		}
	}
?>