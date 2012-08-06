<?php
	/**
	 * Copyable Behavior class file.
	 *
	 * Adds ability to copy a model record, including all hasMany and hasAndBelongsToMany
	 * associations. Relies on Containable behavior, which this behavior will attach
	 * on the fly as needed.
	 *
	 * HABTM relationships are just duplicated in the join table, while hasMany and hasOne
	 * records are recursively copied as well.
	 *
	 * Usage is straightforward:
	 * From model: $this->copy($id); // id = the id of the record to be copied
	 * From container: $this->MyModel->copy($id);
	 *
	 * @author			Jamie Nay
	 * @author			dogmatic69
	 * @copyright       http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @link            http://github.com/jamienay/copyable_behavior
	 */

	class CopyableBehavior extends ModelBehavior {

		/**
		 * Behavior settings
		 *
		 * @access public
		 *
		 * @var array
		 */
		public $settings = array();
		/**
		 * Array of contained models.
		 *
		 * @access public
		 *
		 * @var array
		 */
		public $contain = array();
		/**
		 * The full results of Model::find() that are modified and saved
		 * as a new copy.
		 *
		 * @access public
		 *
		 * @var array
		 */
		public $record = array();
		/**
		 * Default values for settings.
		 *
		 * - recursive: whether to copy hasMany and hasOne records
		 * - habtm: whether to copy hasAndBelongsToMany associations
		 * - stripFields: fields to strip during copy process
		 * - ignore: aliases of any associations that should be ignored, using dot (.) notation.
		 * will look in the $this->contain array.
		 *
		 * @access private
		 *
		 * @var array
		 */
		private $defaults = array(
			'recursive' => true,
			'habtm' => true,
			'stripFields' => array('id', 'created', 'modified', 'lft', 'rght'),
			'ignore' => array()
		);

		/**
		 * Configuration method.
		 *
		 * @access public
		 *
		 * @param object $Model Model object
		 * @param array $config Config array
		 *
		 * @return boolean
		 */
		public function setup($Model, $config = array()) {
			$this->settings[$Model->alias] = array_merge($this->defaults, $config);

			$this->settings[$Model->alias]['recursive'] = (bool)$this->settings[$Model->alias]['recursive'];
			$this->settings[$Model->alias]['habtm'] = (bool)$this->settings[$Model->alias]['habtm'];
			$this->settings[$Model->alias]['stripFields'] = (array)$this->settings[$Model->alias]['stripFields'];
			$this->settings[$Model->alias]['ignore'] = (array)$this->settings[$Model->alias]['ignore'];
			return true;
		}

		/**
		 * Copy method.
		 *
		 * @access public
		 *
		 * @param object $Model model object
		 * @param mixed $id String or integer model ID
		 *
		 * @return boolean
		 */
		public function copy($Model, $id = null) {
			if(!$id) {
				$id = $Model->id;
			}

			if(!$id) {
				return false;
			}

			if(is_array($id)) {
				return $this->__multiCopy($Model, $id);
			}

			$this->generateContain($Model);

			$this->record = $Model->find(
				'first',
				array(
					'conditions' => array($Model->alias . '.id' => $id),
					'contain' => $this->contain
				)
			);

			if (empty($this->record)) {
				return false;
			}

			if (!$this->__convertData($Model)) {
				return false;
			}

			$transaction = $Model->transaction();
			$saved = $this->__copyRecord($Model);

			if($transaction) {
				if($saved) {
					$Model->transaction(true);
				}

				else{
					$Model->transaction(false);
				}
			}

			return $saved;
		}

		/**
		 * @brief copy many rows at a time
		 *
		 * This method is called by copy() when you pass an array of id's to it.
		 * After doing a filter on the passed id's to get only valid ones, it will
		 * start the copying within a transaction if possible.
		 *
		 * The transaction will not be started in copy() if this one is started. Also
		 * transactions will not be started if they were started within the model calling
		 * copy()
		 *
		 * @access private
		 *
		 * @param object $Model the model being copied
		 * @param array $ids array of id's being copied
		 *
		 * @return mixed array of old_id => new_id or false
		 */
		private function __multiCopy($Model, $ids) {
			$ids = array_keys($Model->find('list', array('conditions' => array($Model->alias . '.' . $Model->primaryKey => array_filter((array)$ids)))));

			if(empty($ids)) {
				return false;
			}

			$transaction = $Model->transaction();

			$multiSave = array();
			foreach($ids as $id) {
				$multiSave[] = $this->copy($Model, $id);
			}

			if($transaction && count(array_filter($multiSave)) == count($ids)) {
				$Model->transaction(true);
				return array_combine($ids, $multiSave);
			}

			else if($transaction) {
				$Model->transaction(false);
				return false;
			}
		}

		/**
		 * Wrapper method that combines the results of __recursiveChildContain()
		 * with the models' HABTM associations.
		 *
		 * @access public
		 *
		 * @param object $Model Model object
		 *
		 * @return array
		 */
		public function generateContain($Model) {
			if (!$this->__verifyContainable($Model)) {
				return false;
			}

			$this->contain = array_merge($this->__recursiveChildContain($Model, $Model->alias), array_keys($Model->hasAndBelongsToMany));
			$this->__removeIgnored($Model);
			return $this->contain;
		}

		/**
		 * Removes any ignored associations, as defined in the model settings, from
		 * the $this->contain array.
		 *
		 * @access public
		 *
		 * @param object $Model Model object
		 *
		 * @return boolean
		 */
		private function __removeIgnored($Model) {
			if (!$this->settings[$Model->alias]['ignore']) {
				return true;
			}

			$ignore = array_unique($this->settings[$Model->alias]['ignore']);
			foreach ($ignore as $path) {
				if (Set::check($this->contain, $path)) {
					$this->contain = Set::remove($this->contain, $path);
				}
			}
			return true;
		}

		/**
		 * Strips primary keys and other unwanted fields
		 * from hasOne and hasMany records.
		 *
		 * @access private
		 *
		 * @param object $Model model object
		 * @param array $record
		 *
		 * @return array $record
		 */
		private function __convertChildren($Model, $record) {
			foreach (array_merge($Model->hasMany, $Model->hasOne) as $key => $val) {
				if (!isset($record[$key])) {
					continue;
				}

				if (empty($record[$key])) {
					unset($record[$key]);
					continue;
				}

				if (isset($record[$key][0])) {
					foreach ($record[$key] as $innerKey => $innerVal) {
						$record[$key][$innerKey] = $this->__stripFields($Model->{$key}, $innerVal);

						if (array_key_exists($val['foreignKey'], $innerVal)) {
							unset($record[$key][$innerKey][$val['foreignKey']]);
						}

						$record[$key][$innerKey] = $this->__convertChildren($Model->{$key}, $record[$key][$innerKey]);
					}
				}

				else {
					$record[$key] = $this->__stripFields($Model->{$key}, $record[$key]);

					if (isset($record[$key][$val['foreignKey']])) {
						unset($record[$key][$val['foreignKey']]);
					}

					$record[$key] = $this->__convertChildren($Model->{$key}, $record[$key]);
				}
			}

			return $record;
		}

		/**
		 * Strips primary and parent foreign keys (where applicable)
		 * from $this->record in preparation for saving.
		 *
		 * @access private
		 *
		 * @param object $Model Model object
		 *
		 * @return array $this->record
		 */
		private function __convertData($Model) {
			$this->record[$Model->alias] = $this->__stripFields($Model, $this->record[$Model->alias]);

			$this->record = $this->__convertHabtm($Model, $this->record);
			$this->record = $this->__convertChildren($Model, $this->record);
			return $this->record;
		}

		/**
		 * @brief rename any fields that have a unique index on them
		 *
		 * @access private
		 *
		 * @param object $Model the model being copied
		 * @param array $record the data being copied
		 *
		 * @return array of the modified data
		 */
		private function __renameUniqueFields($Model, $record) {
			foreach(array_keys($record) as $field) {
				if(!$Model->hasField($field)) {
					continue;
				}
				
				$modified = false;
				if(isset($Model->validate[$field]['isUnique'])) {
					$record[$field] = sprintf('%s - copied %s', $record[$field], date('Ymd H:i:s'));
					$modified = true;
				}
				else{
					$index = ConnectionManager::getDataSource($Model->useDbConfig)->index($Model);
					if(isset($index[$field]['unique']) && $index[$field]['unique']) {
						$record[$field] = sprintf('%s - copied %s', $record[$field], date('Ymd H:i:s'));
						$modified = true;
					}
				}

				$count = $Model->find('count', array('conditions' => array($Model->alias . '.' . $field => $record[$field])));
				if($modified === true && $count > 0) {
					$record[$field] = $record[$field] . sprintf(' (%s)', $count);
				}
			}

			return $record;
		}

		/**
		 * Loops through any HABTM results in $this->record and plucks out
		 * the join table info, stripping out the join table primary
		 * key and the primary key of $Model. This is done instead of
		 * a simple collection of IDs of the associated records, since
		 * HABTM join tables may contain extra information (sorting
		 * order, etc).
		 *
		 * @access public
		 *
		 * @param object $Model	Model object
		 *
		 * @return array modified $record
		 */
		private function __convertHabtm($Model, $record) {
			if (!$this->settings[$Model->alias]['habtm']) {
				return $record;
			}

			foreach ($Model->hasAndBelongsToMany as $key => $val) {
				if (!isset($record[$val['className']]) || empty($record[$val['className']])) {
					continue;
				}

				$joinInfo = Set::extract($record[$val['className']], '{n}.' . $val['with']);
				if (empty($joinInfo)) {
					continue;
				}

				foreach ($joinInfo as $joinKey => $joinVal) {
					$joinInfo[$joinKey] = $this->__stripFields($Model, $joinVal);

					if (array_key_exists($val['foreignKey'], $joinVal)) {
						unset($joinInfo[$joinKey][$val['foreignKey']]);
					}
				}

				$record[$val['className']] = $joinInfo;
			}

			return $record;
		}

		/**
		 * Performs the actual creation and save.
		 *
		 * @access private
		 *
		 * @param object $Model Model object
		 *
		 * @return mixed
		 */
		private function __copyRecord($Model) {
			$Model->create();
			$saved = $Model->saveAll($this->record, array('validate' => false));

			$this->record = null;
			return $Model->id;
		}

		/**
		 * Generates a contain array for Containable behavior by
		 * recursively looping through $Model->hasMany and
		 * $Model->hasOne associations.
		 *
		 * @access private
		 *
		 * @param object $Model Model object
		 *
		 * @return array
		 */
		private function __recursiveChildContain($Model, $mainModelAlias = null) {
			if(!isset($this->settings[$Model->alias])) {
				$this->settings[$Model->alias] = $this->settings[$mainModelAlias];
			}

			$contain = array();
			if (!$this->settings[$Model->alias]['recursive']) {
				return $contain;
			}

			$children = array_merge(array_keys($Model->hasMany), array_keys($Model->hasOne));
			foreach ($children as $child) {
				if ($Model->alias == $child) {
					continue;
				}

				$contain[$child] = $this->__recursiveChildContain($Model->{$child}, $Model->alias);
			}

			return $contain;
		}

		/**
		 * Strips unwanted fields from $record, taken from
		 * the 'stripFields' setting.
		 *
		 * @access private
		 *
		 * @param object $Model Model object
		 * @param array $record
		 *
		 * @return array
		 */
		private function __stripFields($Model, $record) {
			foreach($record as $field => $value) {
				if(in_array($field, $this->settings[$Model->alias]['stripFields'])) {
					unset($record[$field]);
				}
			}

			$record = $this->__renameUniqueFields($Model, $record);

			return $record;
		}

		/**
		 * Attaches Containable if it's not already attached.
		 *
		 * @access private
		 *
		 * @param object $Model Model object
		 *
		 * @return boolean
		 */
		private function __verifyContainable($Model) {
			if (!$Model->Behaviors->attached('Containable')) {
				return $Model->Behaviors->attach('Containable');
			}

			return true;
		}
	}