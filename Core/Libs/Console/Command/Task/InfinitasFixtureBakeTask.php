<?php
	/**
	 * @file
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Libs.Vendors.Shells
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	 /**
	  * @brief InfinitasFixtureBakeTask for generating fixtures.
	  *
	  * This task is for generating fixtures in your app. Its similar to the cake
	  * fixture stuff but will also allow you to update your fixtures.
	  */
	class InfinitasFixtureBakeTask extends FixtureTask {
		/**
		 * @brief additional tasks that are used within this task.
		 * @var <type>
		 */
		public $tasks = array('Fixture', 'Model', 'Template');

		/**
		 * @brief generate the fixture specified
		 *
		 * @access public
		 *
		 * @param string $modelName the model to be generated in Plugin.Model format
		 * @param bool $new true if its a new fixture, false if you are updating
		 *
		 * @return see generateFixtureFile()
		 */
		public function setup($modelName = null, $new = true) {
			list($this->plugin, $modelName) = pluginSplit($modelName);
			$this->connection = 'default';

			$useTable = $this->Model->getTable($modelName, $this->connection);
			try{
				$useTable = ClassRegistry::init($this->plugin . '.' . $modelName)->tablePrefix . ClassRegistry::init($this->plugin . '.' . $modelName)->useTable;
			}
			catch(Exception $e) {

			}

			$newFixture = $this->getNewFixture($modelName, $useTable, array());

			if(!$new) {
				$oldFixture = $this->getOldFixture($this->plugin, $modelName);
				$oldFixture['plugin'] = $this->plugin;

				if(!$oldFixture) {
					$this->err(sprintf('There is no fixture for %s', $modelName));
					return false;
				}

				$addFields = array_diff(array_keys($newFixture['fixture']['schema']), array_keys($oldFixture['fields']));
				$removeFields = array_diff(array_keys($oldFixture['fields']), array_keys($newFixture['fixture']['schema']));

				foreach($newFixture['fixture']['schema'] as $newField => $config) {
					if(!isset($oldFixture['fields'][$newField])) {
						$oldFixture['fields'][$newField] = $config;
						continue;
					}

					$oldFixture['fields'][$newField] = array_merge($oldFixture['fields'][$newField], $config);
				}

				foreach($oldFixture['fields'] as $field => $config) {
					if(in_array($field, $removeFields)) {
						unset($oldFixture['fields'][$field]);
					}
				}
			}

			// move the table params to the end
			$oldTableParams = $oldFixture['fields']['indexes'];
			unset($oldFixture['fields']['indexes']);
			$oldFixture['fields']['indexes'] = $oldTableParams;

			$oldTableParams = $oldFixture['fields']['tableParameters'];
			unset($oldFixture['fields']['tableParameters']);
			$oldFixture['fields']['tableParameters'] = $oldTableParams;

			$oldFixture['records'] = $this->__cleanupRecords($oldFixture, $addFields, $removeFields);

			return $this->generateFixtureFile($modelName, $oldFixture);
		}

		/**
		 * @brief clean up the old fixture, merging the new data with it
		 *
		 * This method will remove any fields that are no longer present in the
		 * database and remove any fields in the fixture data that are not needed
		 *
		 * It will also add any new fields that were found in the database to the
		 * fixture and data.
		 *
		 * @access public
		 *
		 * @param array $fixture the old version of the fixture
		 * @param array $new list of new fields (to add)
		 * @param array $old list of old fields (to remove)
		 *
		 * @return array the old fixture with new stuff merged in
		 */
		private function __cleanupRecords($fixture, $new, $old) {
			foreach($new as $newField) {
				foreach($fixture['records'] as $k => $record) {
					$fixture['records'][$k][$newField] = null;
				}
			}

			foreach($old as $oldField) {
				foreach($fixture['records'] as $k => $record) {
					unset($fixture['records'][$k][$oldField]);
				}
			}

			return $fixture['records'];
		}

		/**
		 * @brief generate the data for a new fixture
		 *
		 * @access public
		 *
		 * @param string $model the model to generate the fixture for
		 * @param string $useTable the table that the model uses
		 * @param array $importOptions options for the import
		 *
		 * @return array of fixture data
		 */
		public function getNewFixture($model, $useTable = false, $importOptions = array()) {
			if (!class_exists('CakeSchema')) {
				App::import('Model', 'CakeSchema', false);
			}

			$table = $schema = $records = $import = $modelImport = null;
			$importBits = array();

			if (!$useTable) {
				$useTable = Inflector::tableize($model);
			}

			else if ($useTable != Inflector::tableize($model)) {
				$table = $useTable;
			}

			$this->_Schema = new CakeSchema();
			$data = $this->_Schema->read(array('models' => false, 'connection' => $this->connection));

			if (!isset($data['tables'][$useTable])) {
				$this->err('Could not find your selected table ' . $useTable);
				return false;
			}

			$schema = $data['tables'][$useTable];
			$records = array();

			$fixture = compact('records', 'table', 'schema', 'import', 'fields');

			$fixture = array(
				'model' => $model,
				'fixture' => $fixture
			);

			return $fixture;
		}

		/**
		 * @brief read the data from an existing fixture
		 *
		 * @access public
		 *
		 * @param string $plugin the name of the plugin
		 * @param string $model the name of the model
		 *
		 * @return array details of the fixture (records, fields etc)
		 */
		public function getOldFixture($plugin, $model) {
			$file = App::pluginPath($plugin) . 'Test' . DS . 'Fixture' . DS . $model . 'Fixture.php';
			if(!is_file($file)) {
				return array();
			}

			$cakeFixture = CAKE_CORE_INCLUDE_PATH . DS . 'Cake' . DS . 'TestSuite' . DS . 'Fixture' . DS . 'CakeTestFixture.php';
			require_once $cakeFixture;
			require_once $file;

			$fixtureClass = $model . 'Fixture';

			return get_class_vars($fixtureClass);
		}

		/**
		 * @generate a fixture
		 *
		 * This method will first convert the arrays to flat text and then pass it
		 * to cakes normal fixture writing method.
		 *
		 * @access public
		 *
		 * @param string $model the
		 * @param array $options the data for the fixture
		 *
		 * @return see FixtureTask::generateFixtureFile()
		 */
		public function generateFixtureFile($model, $options) {
			$options['fields'] = explode("\n", $this->_generateSchema($options['fields']));
			$options['fields'] = implode("\n", $options['fields']);

			$options['schema'] = $options['fields'];

			$options['records'] = explode("\n", $this->_makeRecordString($options['records']));
			$options['records'] = implode("\n", $options['records']);

			return parent::generateFixtureFile($model, $options);
		}
	}