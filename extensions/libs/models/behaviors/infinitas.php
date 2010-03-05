<?php
	class InfinitasBehavior extends ModelBehavior {
		var $_defaults = array();

		function setup(&$Model, $config = null) {
			if (is_array($config)) {
				$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
			} else {
				$this->settings[$Model->alias] = $this->_defaults;
			}
		}


		/**
		 * Get all the tables
		 *
		 * @param array $Model
		 * @param string $connection the connection to use for getting tables.
		 *
		 * @return array list of tables.
		 */
		function getTables(&$Model, $connection = 'default'){
			$this->db    = ConnectionManager::getDataSource($connection);
			$tables      = $this->db->query('SHOW TABLES;');
			$databseName = $this->db->config['database'];

			unset($this->db);

			return Set::extract('/TABLE_NAMES/Tables_in_'.$databseName, $tables);
		}

		/**
		 * Get tables with a certain field.
		 *
		 * Gets a list of tables with the selected field in the selected connection.
		 *
		 * @param mixed $Model
		 * @param string $connection the connection to use when finding tables
		 * @param mixed $field the tables with this field are returned
		 *
		 * @return false when no field set, else array of tables with model/plugin.
		 */
		function getTablesByField(&$Model, $connection = 'default', $field = null){
			if (!$field) {
				return false;
			}

			$tableNames = $this->getTables($Model, $connection);
			$return = array();

			$this->db    = ConnectionManager::getDataSource($connection);

			foreach($tableNames as $table ){
				$fields = $this->db->query('DESCRIBE '.$table);
				$fields = Set::extract('/COLUMNS/Field', $fields);

				if (in_array($field, $fields)) {
					$_table = explode('_', $table, 2);

					$plugin = ucfirst(count($_table) == 2 ? $_table[0] : '');
					$plugin = ($plugin == 'Core') ? 'Management' : $plugin;

					$return[] = array(
						'plugin' => $plugin,
						'model'  => Inflector::classify(isset($_table[1]) ? $_table[1] : $_table[0]),
						'table'  => $table
					);
				}
			}
			return $return;
		}
	}
?>