<?php
/**
 * BigDataBehavior
 *
 * @package Infinitas.Data.Model.Behavior
 */

/**
 * BigDataBehavior
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Data.Model.Behavior
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class BigDataBehavior extends ModelBehavior {
/**
 * default config for the behavior
 *
 * @var array
 */
	protected $_defaultConfig = array(
		'validate' => true,
		'chunk' => 500,
		'transaction' => true,
		'disableIndex' => true
	);

/**
 * Indexing settings
 *
 * @var array
 */
	protected $_inexing = array(
		'innodb' => array(
			'unique_checks',
			'autocommit',
			'foreign_key_checks',
		)
	);

/**
 * Datasource instance
 *
 * @var DataSource
 */
	protected $_db = null;

/**
 * setup the model based on the supplied configs
 *
 * @param Model $Model the model being loaded
 * @param array $config config options
 */
	public function setup(Model $Model, $config = array()) {
		$this->_settings[$Model->alias] = array(
			'config' => array(

			),
			'engine' => null,
			'saveTemplate' => 'INSERT INTO `%s` (%s) VALUES %s;'
		);
		$this->_settings[$Model->alias]['config'] = array_merge(
			$this->_defaultConfig,
			$config
		);
	}

/**
 * Get the database engine type
 *
 * @param Model $Model
 *
 * @return string
 */
	public function engineType(Model $Model) {
		if(empty($this->_settings[$Model->alias]['engine'])) {
			$return = current((array)$Model->query(
				sprintf(
					'SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_NAME = "%s"', $Model->fullTableName(null, false)
				)
			));

			if(!empty($return['TABLES']['ENGINE'])) {
				$this->_settings[$Model->alias]['engine'] = strtolower($return['TABLES']['ENGINE']);
			}
		}

		return $this->_settings[$Model->alias]['engine'];
	}

/**
 * toggle services on the database
 *
 * @see http://dev.mysql.com/doc/refman/5.0/en/innodb-tuning.html
 *
 * @param Model $Model the model object being used
 * @param string|array $type the service being changed
 * @param boolean $on service should be enabled or disabled
 *
 * @return void
 *
 * @throws InvalidArgumentException
 */
	protected function _toggle(Model $Model, $type, $on = false) {
		if(is_array($type)) {
			foreach($type as $k => $v) {
				$this->_toggle($Model, $k, $v);
			}
			return;
		}

		if($this->engineType($Model) == 'innodb') {
			switch($type) {
				case 'unique_checks':
				case 'foreign_key_checks':
					$Model->query(sprintf('SET %s = %d;', $type, (int)$on));
					break;

				case 'autocommit':
					if(!$on) {
						$Model->query(sprintf('SET %s = %d;', $type, (int)$on));
					} else {
						$Model->query('COMMIT;');
					}
					break;

				default:
					throw new InvalidArgumentException(sprintf('Unknown command "%s"', $type));
					break;
			}
			return;
		}
		if($this->engineType($Model) == 'myisam') {
			switch($type) {
				case 'keys':
					$Model->query(sprintf('ALTER TABLE %s %s KEYS;', $Model->fullTableName(), $on ? 'ENABLE' : 'DISABLE'));
					break;

				case 'autocommit':
				case 'foreign_key_checks':
					$Model->query(sprintf('SET %s = %d;', $type, (int)$on));
					break;

				default:
					throw new InvalidArgumentException(sprintf('Unknown command "%s"', $type));
					break;
			}
		}
	}

/**
 * disable indexing to speed up mass inserts
 *
 * @param Model $Model the model object in use
 * @param array $types options to disable
 *
 * @return void
 */
	public function disableIndexing(Model $Model, $types = array()) {
		$types = array_merge(
			$this->_inexing[$this->engineType($Model)],
			$types
		);

		$this->_toggle($Model, array_fill_keys($types, false));
	}

/**
 * enable indexing after doing mass saves
 *
 * @param Model $Model the model object in use
 * @param array $types options to enable
 *
 * @return void
 */
	public function enableIndexing(Model $Model, $types = array()) {
		$types = array_merge(
			$this->_inexing[$this->engineType($Model)],
			$types
		);

		$this->_toggle($Model, array_fill_keys($types, true));
	}

/**
 * access the data source
 *
 * @param Model $Model
 *
 * @return DataSource
 */
	protected function _db(Model $Model) {
		if(!$this->_db) {
			$this->_db = $Model->getDataSource();
		}

		return $this->_db;
	}

/**
 * mass save rows
 *
 * Created and modified times will be set much like normal saves. Fields
 * that do not belong will be removed from the data.
 *
 * Options:
 *  - validate: use the models validation rules before saving (this
 *		will not work for unique etc within the data being saved. Mostly
 *		just with things like checking the field types are correct
 *  - chunk: how many records to save at a time
 *  - transaction: should use transactions or not.
 *
 * @param type $data the data being saved, accepts the same format as Model::find() returns or Model::saveAll() accepts
 * @param type $options options for the save
 *
 * @return boolean
 *
 * @throws CakeException
 */
	public function rawSave(Model $Model, $data, $options = array()) {
		$options = array_merge(
			$this->_settings[$Model->alias]['config'],
			$options
		);

		if(empty($data)) {
			return true;
		}

		if(!empty($data[0][$Model->alias])) {
			$data = Set::extract('{n}.' . $Model->alias, $data);
		}

		$fields = array_keys(current($data));
		foreach(array('created', 'modified') as $dateField) {
			if(!in_array($dateField, $fields) && $Model->hasField($dateField)) {
				$fields[] = $dateField;

				array_walk($data, function(&$row) use($dateField) {
					$row[$dateField] = date('Y-m-d H:i:s');
				});
			}
		}

		$primaryKeySchema = $Model->schema($Model->primaryKey);

		if($primaryKeySchema['type'] == 'string' && !in_array($Model->primaryKey, $fields)) {
			$fields[] = $pk = $Model->primaryKey;
			array_walk($data, function(&$row) use($pk) {
				$row[$pk] = String::uuid();
			});
		}

		$schema = array_keys($Model->schema());

		foreach(array_diff($fields, $schema) as $unknownField) {
			array_walk($data, function(&$row) use($unknownField) {
				unset($row[$unknownField]);
			});
		}

		$schema = array_intersect($schema, $fields);
		sort($schema);

		foreach($data as $k => $row) {
			foreach ($row as $field => $value) {
				$row[$field] = $this->_db($Model)->value($value, $field);
			}

			if($options['validate']) {
				$Model->set($row);
				if(!$Model->validates()) {
					throw new CakeException(sprintf('Row %d does not validate', $k + 1));
				}
			}
			ksort($row);
			$data[$k] = "(" . implode(", ", $row) . ")";
		}

		if($options['transaction']) {
			$options['transaction'] = $Model->transaction();
		}

		if($options['disableIndex']) {
			$indexOptions = array();
			if(is_array($options['disableIndex'])) {
				$indexOptions = $options['disableIndex'];
			}

			$this->disableIndexing($Model, $indexOptions);
		}

		$saved = true;
		foreach(array_chunk($data, $options['chunk']) as $dataChunk) {
			$query = sprintf(
				$this->_settings[$Model->alias]['saveTemplate'],
				$Model->fullTableName(null, false),
				'`' . implode('`, `', $schema) . '`',
				implode(', ', $dataChunk)
			);
			$saved = $saved && $this->_db($Model)->rawQuery($query);
		}

		if($options['disableIndex']) {
			$this->enableIndexing($Model, $indexOptions);
		}

		if($options['transaction']) {
			$Model->transaction(true);
		}

		return $saved;
	}

}