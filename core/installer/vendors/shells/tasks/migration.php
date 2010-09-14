<?php

App::import('Model', 'Installer.CakeSchema', false);

class MigrationTask extends Shell {
	public $connection = 'default';
	public $type = 'app';

	/**
	 * Generate a new migration file
	 *
	 * @return void
	 * @access public
	 */
	public function generate($plugin = 'app') {
		$this->type = Inflector::underscore($plugin);
		$this->path = $this->__getPath() . 'config' . DS . 'releases' . DS;

		$fromSchema = false;
		$this->Schema = $this->_getSchema();
		$migration = array('up' => array(), 'down' => array());

		$oldSchema = $this->_getSchema($this->type);
		if ($oldSchema !== false) {
			if ($this->type !== 'migrations') {
				unset($oldSchema->tables['schema_migrations']);
			}
			$schema = $newSchema = $this->_readSchema();
			$comparison = $this->Schema->compare($oldSchema, $newSchema);
			$migration = $this->_fromComparison($migration, $comparison, $oldSchema->tables, $newSchema['tables']);
			$fromSchema = true;
		} else {
			$schema = $dump = $this->_readSchema();
			$dump = $dump['tables'];
			unset($dump['missing']);

			if (!empty($dump)) {
				$migration['up']['create_table'] = $dump;
				$migration['down']['drop_table'] = array_keys($dump);
			}
			$fromSchema = true;
		}

		if(isset($schema)) {
			$schema['name'] = $plugin;
			$this->Schema->write($schema);
		}

		return $this->_makeMigrationString($migration);
	}


/**
 * Return the path used
 *
 * @param string $type Can be 'app' or a plugin name
 * @return string Path used
 * @access private
 */
	private function __getPath($type = null) {
		if ($type === null) {
			$type = $this->type;
		}
		if ($type != 'app') {
			return App::pluginPath($type);
		}
		return APP;
	}

/**
 * Generate a migration string using comparison
 *
 * @param array $migration Migration instructions array
 * @param array $comparison Result from CakeSchema::compare()
 * @param array $oldTables List of tables on schema.php file
 * @param array $currentTables List of current tables on database
 * @return array
 * @access protected
 */
	protected function _fromComparison($migration, $comparison, $oldTables, $currentTables) {
		foreach ($comparison as $table => $actions) {
			if (!isset($oldTables[$table])) {
				$migration['up']['create_table'][$table] = $actions['add'];
				$migration['down']['drop_table'][] = $table;
				continue;
			}

			foreach ($actions as $type => $fields) {
				$indexes = array();
				if (!empty($fields['indexes'])) {
					$indexes = array('indexes' => $fields['indexes']);
					unset($fields['indexes']);
				}

				if ($type == 'add') {
					$migration['up']['create_field'][$table] = array_merge($fields, $indexes);

					$migration['down']['drop_field'][$table] = array_keys($fields);
					if (!empty($indexes['indexes'])) {
						$migration['down']['drop_field'][$table]['indexes'] = array_keys($indexes['indexes']);
					}
				} else if ($type == 'change') {
					$migration['up']['alter_field'][$table] = $fields;
					$migration['down']['alter_field'][$table] = array_intersect_key($oldTables[$table], $fields);
				} else {
					$migration['up']['drop_field'][$table] = array_keys($fields);
					if (!empty($indexes['indexes'])) {
						$migration['up']['drop_field'][$table]['indexes'] = array_keys($indexes['indexes']);
					}

					$migration['down']['create_field'][$table] = array_merge($fields, $indexes);
				}
			}
		}

		foreach ($oldTables as $table => $fields) {
			if (!isset($currentTables[$table])) {
				$migration['up']['drop_table'][] = $table;
				$migration['down']['create_table'][$table] = $fields;
			}
		}
		return $migration;
	}

/**
 * Load and construct the schema class if exists
 *
 * @param string $type Can be 'app' or a plugin name
 * @return mixed False in case of no file found, schema object
 * @access protected
 */
	protected function _getSchema($type = null) {
		if ($type === null) {
			$plugin = ($this->type === 'app') ? null : $this->type;
			return new CakeSchema(array('connection' => $this->connection, 'plugin' => $plugin, 'path' => $this->__getPath($type) . 'config' . DS . 'schema' . DS));
		}
		$file = $this->__getPath($type) . 'config' . DS . 'schema' . DS . 'schema.php';
		if (!file_exists($file)) {
			return false;
		}
		require_once $file;

		$name = Inflector::camelize($type) . 'Schema';
		if ($type == 'app' && !class_exists($name)) {
			$name = Inflector::camelize($this->params['app']) . 'Schema';
		}

		$plugin = ($type === 'app') ? null : $type;
		$schema = new $name(array('connection' => $this->connection, 'plugin' => $plugin));
		return $schema;
	}

/**
 * Reads the schema data
 *
 * @return array
 * @access protected
 */
	protected function _readSchema() {
		$read = $this->Schema->read(array('models' => !isset($this->params['f']), 'ignoreRelations' => true, 'ignorePrefix' => true));

		if ($this->type !== 'migrations') {
			unset($read['tables']['schema_migrations']);
		}

		if($this->type !== 'app' && !isset($this->params['f'])) {
			$systemTables = array(
						'aros', 'acos', 'aros_acos', Configure::read('Session.table'), 'i18n'
					);

			$read['tables'] = array_diff_key($read['tables'], array_fill_keys($systemTables, 1));
		}

		return $read;
	}

/**
 * Generate and write a migration with given name
 *
 * @param string $name Name of migration
 * @param string $class Class name of migration
 * @param array $migration Migration instructions array
 * @return boolean
 * @access protected
 */
	protected function _makeMigrationString($migration) {
		$content = '';
		foreach ($migration as $direction => $actions) {
			$content .= "\t\t'" . $direction . "' => array(\n";
			foreach ($actions as $type => $tables) {
				$content .= "\t\t\t'" . $type . "' => array(\n";
				if ($type == 'create_table' || $type == 'create_field' || $type == 'alter_field') {
					foreach ($tables as $table => $fields) {
						$content .= "\t\t\t\t'" . $table . "' => array(\n";
						foreach ($fields as $field => $col) {
							if ($field == 'indexes') {
								$content .= "\t\t\t\t\t'indexes' => array(\n";
								foreach ($col as $index => $key) {
									$content .= "\t\t\t\t\t\t'" . $index . "' => array(" . implode(', ',  $this->__values($key)) . "),\n";
								}
								$content .= "\t\t\t\t\t),\n";
							} else {
								$content .= "\t\t\t\t\t'" . $field . "' => ";
								if (is_string($col)) {
									$content .= "'" . $col . "',\n";
								} else {
									$content .= 'array(' . implode(', ',  $this->__values($col)) . "),\n";
								}
							}
						}
						$content .= "\t\t\t\t),\n";
					}
				} else if ($type == 'drop_table') {
					$content .= "\t\t\t\t'" . implode("', '", $tables) . "'\n";
				} else if ($type == 'drop_field') {
					foreach ($tables as $table => $fields) {
						$indexes = array();
						if (!empty($fields['indexes'])) {
							$indexes = $fields['indexes'];
						}
						unset($fields['indexes']);

						$content .= "\t\t\t\t'" . $table . "' => array('" . implode("', '", $fields) . "',";
						if (!empty($indexes)) {
							$content .= " 'indexes' => array('" . implode("', '", $indexes) . "')";
						}
						$content .= "),\n";
					}
				}
				$content .= "\t\t\t),\n";
			}
			$content .= "\t\t),\n";
		}

		return $content;
	}

/**
 * Format a array/string into a one-line syntax
 *
 * @param array $values Array to be converted
 * @return string
 * @access private
 */
	private function __values($values) {
		$_values = array();
		if (is_array($values)) {
			foreach ($values as $key => $value) {
				if (is_array($value)) {
					$_values[] = "'" . $key . "' => array('" . implode("', '",  $value) . "')";
				} else if (!is_numeric($key)) {
					$value = var_export($value, true);
					$_values[] = "'" . $key . "' => " . $value;
				}
			}
		}
		return $_values;
	}
}