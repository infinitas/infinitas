<?php
/**
 * InfinitasFixtureTask
 *
 * @package Infinitas.Installer.Console.Task
 */

/**
 * InfinitasFixtureTask
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Installer.Console.Task
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class InfinitasFixtureTask extends Shell {
/**
 * Connection to use
 *
 * @var string
 */
	public $connection = 'default';

/**
 * Generate a fixture
 *
 * @param array $models the models to generate fixtures for
 * @param string $plugin the plugin to generate a fixture for
 *
 * @return string
 */
	public function generate($models = null, $plugin = null) {
		if ($models == null) {
			$models = array('Aco', 'Aro', 'ArosAco', 'Session');
		}

		$fixtures = array();
		foreach ($models as $model => $options) {
			if (is_string($options)) {
				$model = $options;
				$options = array(
					'core' => array(
						'where' => '1=1',
						'limit' => 0
					)
				);
			}

			foreach ($options as $type => $option) {
				$modelName = $plugin . '.' . $model;
				$conditions = $option['where'];

				if ($option['limit'] != 0) {
					$conditions .= ' LIMIT ' . $option['limit'];
				}

				$records = $this->_getRecordsFromTable($modelName, $conditions);
				if (!empty($records)) {
					$fixtures[$type][$model] = $records;
				}
			}
		}

		return $this->_makeRecordString($fixtures);
	}

/**
 * Get records to fill fixture data
 *
 * Interact with the user to get a custom SQL condition and use that to extract data
 * to build a fixture.
 *
 * @param string $modelName name of the model to take records from.
 * @param string $connection the connection to use
 * @param string $useTable Name of table to use.
 *
 * @return array
 */
	function _getRecordsFromTable($modelName, $condition, $useTable = null) {
		$ModelObject = ClassRegistry::init($modelName);

		$out = array();
		if (is_object($ModelObject) && isset($ModelObject->useTable) && $ModelObject->useTable !== false) {
			$records = $ModelObject->find(
				'all',
				array(
					'conditions' => $condition,
					'recursive' => -1
				)
			);

			$db = ConnectionManager::getDataSource($ModelObject->useDbConfig);
			$schema = $ModelObject->schema(true);
			if (!empty($records)) {
				foreach ($records as $record) {
					$row = array();
					foreach ($record[$ModelObject->alias] as $field => $value) {
						if (isset($schema[$field])) {
							$row[$field] = $db->value($value, $schema[$field]['type']);
						}
					}

					$out[] = $row;
				}
			}
		}
		return $out;
	}

/**
 * Convert a $records array into a a string.
 *
 * @param array $records Array of records to be converted to string
 * 
 * @return string
 */
	function _makeRecordString($records) {
		$out = '';
		foreach ($records as $type => $models) {
			$out .= "\t'$type' => array(\n";
			foreach ($models as $model => $modelRecords) {
				$out .= "\t\t'$model' => array(\n";
				foreach ($modelRecords as $record) {
					$values = array();
					foreach ($record as $field => $value) {
						$values[] = "\t\t\t\t'$field' => $value";
					}
					$out .= "\t\t\tarray(\n";
					$out .= implode(",\n", $values);
					$out .= "\n\t\t\t),\n";
				}
				$out .= "\t\t),\n";
			}
			$out .= "\t\t),\n";
		}

		return $out;
	}

}