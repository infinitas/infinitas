<?php
	class InfinitasFixtureBakeTask extends FixtureTask {
		public $tasks = array('Fixture', 'Model', 'Template');

		public function setup($modelName = null, $new = true){
			list($this->plugin, $modelName) = pluginSplit($modelName);
			$this->connection = 'default';
			$useTable = $this->Model->getTable($modelName, $this->connection);

			$newFixture = $this->getNewFixture($modelName, $useTable, array());
			
			if(!$new) {
				$oldFixture = $this->getOldFixture($this->plugin, $modelName);
				
				if(!$oldFixture){
					$this->err(sprintf('There is no fixture for %s', $modelName));
					return false;
				}

				$addFields = array_diff(array_keys($newFixture['fixture']['schema']), array_keys($oldFixture['fields']));
				$removeFields = array_diff(array_keys($oldFixture['fields']), array_keys($newFixture['fixture']['schema']));

				foreach($newFixture['fixture']['schema'] as $newField => $config){
					if(!isset($oldFixture['fields'][$newField])){
						$oldFixture['fields'][$newField] = $config;
						continue;
					}

					$oldFixture['fields'][$newField] = array_merge($oldFixture['fields'][$newField], $config);
				}

				foreach($oldFixture['fields'] as $field => $config){
					if(in_array($field, $removeFields)){
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

		private function __cleanupRecords($fixture, $new, $old){
			foreach($new as $newField){
				foreach($fixture['records'] as $k => $record){
					$fixture['records'][$k][$newField] = 'null';
				}
			}
			
			foreach($old as $oldField){
				foreach($fixture['records'] as $k => $record){
					unset($fixture['records'][$k][$oldField]);
				}
			}

			foreach($fixture['records'] as $k => $record){
				foreach($record as $field => $value){
					if(!is_int($value)){
						$fixture['records'][$k][$field] = sprintf("'%s'", $fixture['records'][$k][$field]);
					}
				}
			}

			return $fixture['records'];
		}

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

		public function getOldFixture($plugin, $model){
			$file = App::pluginPath($plugin) . 'tests' . DS . 'fixtures' . DS . Inflector::underscore($model) . '_fixture.php';
			if(!is_file($file)){
				return array();
			}

			$cakeFixture = CAKE_CORE_INCLUDE_PATH . DS . 'cake' . DS . 'tests' . DS . 'lib' . DS . 'cake_test_fixture.php';
			require $cakeFixture;
			require $file;

			$fixtureClass = $model . 'Fixture';

			return get_class_vars($fixtureClass);
		}

		public function generateFixtureFile($model, $options){
			$options['fields'] = explode("\n", $this->_generateSchema($options['fields']));
			foreach($options['fields'] as $k => $line){
				$options['fields'][$k] = "\t" . $options['fields'][$k];
			}
			$options['fields'] = implode("\n", $options['fields']);

			$options['schema'] = $options['fields'];
			
			$options['records'] = explode("\n", $this->_makeRecordString($options['records']));
			foreach($options['records'] as $k => $line){
				$options['records'][$k] = "\t" . $options['records'][$k];
			}
			$options['records'] = implode("\n", $options['records']);

			return parent::generateFixtureFile($model, $options);
		}
	}