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

		function getTableList($connection = 'default'){
			$this->db    = ConnectionManager::getDataSource($connection);
			$tables      = $this->db->query('SHOW TABLES;');
			$databseName = $this->db->config['database'];

			unset($this->db);

			return Set::extract('/TABLE_NAMES/Tables_in_'.$databseName, $tables);
		}
	}
?>