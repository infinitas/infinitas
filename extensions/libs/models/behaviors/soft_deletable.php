<?php
/**
 * SoftDeletable Behavior class file.
 *
 * @filesource
 * @author Mariano Iglesias
 * @link http://cake-syrup.sourceforge.net/ingredients/soft-deletable-behavior/
 * @version	$Revision: 966 $
 * @license	http://www.opensource.org/licenses/mit-license.php The MIT License
 * @package app
 * @subpackage app.models.behaviors
 */

/**
 * Model behavior to support soft deleting records.
 *
 * @package app
 * @subpackage app.models.behaviors
 */
class SoftDeletableBehavior extends ModelBehavior {
	/**
	 * Stores the result of the previous soft delete.
	 *
	 * @var tristate
	 */
	private $__result = null;

	/**
	 * Initiate behaviour for the model using settings.
	 *
	 * @param object $model Model using the behaviour
	 * @param array $settings Settings to override for model.
	 */
	public function setup($model, $settings = array()) {
		$default = array('field' => 'deleted', 'field_date' => 'deleted_date', 'delete' => true, 'find' => true);

		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = $default;
		}

		$this->settings[$model->alias] = array_merge($this->settings[$model->alias], (is_array($settings) ? $settings : array()));
	}

	/**
	 * Run before a model is deleted, used to do a soft delete when needed.
	 *
	 * @param object $model Model about to be deleted
	 * @param boolean $cascade If true records that depend on this record will also be deleted
	 * @return boolean Set to true to continue with delete, false otherwise
	 */
	public function beforeDelete($model, $cascade = true) {
		$this->__result = null;

		if ($this->settings[$model->alias]['delete'] && $model->hasField($this->settings[$model->alias]['field'])) {
			$this->__result = $this->softDelete($model, $model->id, $cascade);
			return false;
		}

		return true;
	}

	/**
	 * Soft deletes a record.
	 *
	 * @param object $model Model from where the method is being executed.
	 * @param mixed $id ID of the soft-deleted record.
	 * @param boolean $cascade Also delete dependent records
	 * @return boolean Result of the operation.
	 */
	public function softDelete($model, $id, $cascade = false) {
		$attributes = $this->settings[$model->alias];
		$data = array($model->alias => array(
			$attributes['field'] => 1
		));

		if (isset($attributes['field_date']) && $model->hasField($attributes['field_date'])) {
			$data[$model->alias][$attributes['field_date']] = date('Y-m-d H:i:s');
		}

		foreach(array_merge(array_keys($data[$model->alias]), array('field', 'field_date', 'find', 'delete')) as $field) {
			unset($attributes[$field]);
		}

		if (!empty($attributes)) {
			$data[$model->alias] = array_merge($data[$model->alias], $attributes);
		}

		if (method_exists($model, 'beforeSoftDeletable') && $model->beforeSoftDeletable($id) === false) {
			return false;
		}

		$model->id = $id;
		$deleted = $model->save($data, false, array_keys($data[$model->alias]));

		if (method_exists($model, 'afterSoftDeletable')) {
			$model->afterSoftDeletable($id);
		}

		if ($deleted && $cascade) {
			$model->_deleteDependent($id, $cascade);
			$model->_deleteLinks($id);
		}

		return !empty($deleted);
	}

	/**
	 * Returns the result of the previous soft delete and results the result variable
	 *
	 * @return boolean
	 */
	public function checkResult() {
		$result = $this->__result;
		$this->__result = null;

		return $result === true;
	}

	/**
	 * Permanently deletes a record.
	 *
	 * @param object $model Model from where the method is being executed.
	 * @param mixed $id ID of the soft-deleted record.
	 * @param boolean $cascade Also delete dependent records
	 * @return boolean Result of the operation.
	 */
	public function hardDelete($model, $id, $cascade = true) {
		$onFind = $this->settings[$model->alias]['find'];
		$onDelete = $this->settings[$model->alias]['delete'];
		$this->enableSoftDeletable($model, false);

		$deleted = $model->delete($id, $cascade);

		$this->enableSoftDeletable($model, 'delete', $onDelete);
		$this->enableSoftDeletable($model, 'find', $onFind);

		return $deleted;
	}

	/**
	 * Permanently deletes all records that were soft deleted.
	 *
	 * @param object $model Model from where the method is being executed.
	 * @param boolean $cascade Also delete dependent records
	 * @return boolean Result of the operation.
	 */
	public function purge($model, $cascade = true) {
		$purged = false;

		if ($model->hasField($this->settings[$model->alias]['field'])) {
			$onFind = $this->settings[$model->alias]['find'];
			$onDelete = $this->settings[$model->alias]['delete'];
			$this->enableSoftDeletable($model, false);

			$purged = $model->deleteAll(array($this->settings[$model->alias]['field'] => '1'), $cascade);

			$this->enableSoftDeletable($model, 'delete', $onDelete);
			$this->enableSoftDeletable($model, 'find', $onFind);
		}

		return $purged;
	}

	/**
	 * Restores a soft deleted record, and optionally change other fields.
	 *
	 * @param object $model Model from where the method is being executed.
	 * @param mixed $id ID of the soft-deleted record.
	 * @param $attributes Other fields to change (in the form of field => value)
	 * @return boolean Result of the operation.
	 */
	public function undelete($model, $id = null, $attributes = array()) {
		if ($model->hasField($this->settings[$model->alias]['field'])) {
			if (empty($id)) {
				$id = $model->id;
			}

			$data = array($model->alias => array(
				$model->primaryKey => $id,
				$this->settings[$model->alias]['field'] => '0'
			));

			if (isset($this->settings[$model->alias]['field_date']) && $model->hasField($this->settings[$model->alias]['field_date'])) {
				$data[$model->alias][$this->settings[$model->alias]['field_date']] = null;
			}

			if (!empty($attributes)) {
				$data[$model->alias] = array_merge($data[$model->alias], $attributes);
			}

			$onFind = $this->settings[$model->alias]['find'];
			$onDelete = $this->settings[$model->alias]['delete'];
			$this->enableSoftDeletable($model, false);

			$model->id = $id;
			$result = $model->save($data, false, array_keys($data[$model->alias]));

			$this->enableSoftDeletable($model, 'find', $onFind);
			$this->enableSoftDeletable($model, 'delete', $onDelete);

			return ($result !== false);
		}

		return false;
	}

	/**
	 * Set if the beforeFind() or beforeDelete() should be overriden for specific model.
	 *
	 * @param object $model Model about to be deleted.
	 * @param mixed $methods If string, method (find / delete) to enable on, if array array of method names, if boolean, enable it for find method
	 * @param boolean $enable If specified method should be overriden.
	 */
	public function enableSoftDeletable($model, $methods, $enable = true) {
		if (is_bool($methods)) {
			$enable = $methods;
			$methods = array('find', 'delete');
		}

		if (!is_array($methods)) {
			$methods = array($methods);
		}

		foreach($methods as $method) {
			$this->settings[$model->alias][$method] = $enable;
		}
	}

	/**
	 * Run before a model is about to be find, used only fetch for non-deleted records.
	 *
	 * @param object $model Model about to be deleted.
	 * @param array $queryData Data used to execute this query, i.e. conditions, order, etc.
	 * @return mixed Set to false to abort find operation, or return an array with data used to execute query
	 */
	public function beforeFind($model, $queryData) {
		if ($this->settings[$model->alias]['find'] && $model->hasField($this->settings[$model->alias]['field'])) {
			$Db =& ConnectionManager::getDataSource($model->useDbConfig);
			$include = false;

			if (!empty($queryData['conditions']) && is_string($queryData['conditions'])) {
				$include = true;

				$fields = array(
					$Db->name($model->alias) . '.' . $Db->name($this->settings[$model->alias]['field']),
					$Db->name($this->settings[$model->alias]['field']),
					$model->alias . '.' . $this->settings[$model->alias]['field'],
					$this->settings[$model->alias]['field']
				);

				foreach($fields as $field) {
					if (preg_match('/^' . preg_quote($field) . '[\s=!]+/i', $queryData['conditions']) || preg_match('/\\x20+' . preg_quote($field) . '[\s=!]+/i', $queryData['conditions'])) {
						$include = false;
						break;
					}
				}
			} else if (empty($queryData['conditions']) || (
					!in_array($this->settings[$model->alias]['field'], array_keys($queryData['conditions']), true) &&
					!in_array($model->alias . '.' . $this->settings[$model->alias]['field'], array_keys($queryData['conditions']), true)
			)) {
				$include = true;
			}

			if ($include) {
				if (empty($queryData['conditions'])) {
					$queryData['conditions'] = array();
				}

				if (is_string($queryData['conditions'])) {
					$queryData['conditions'] = $Db->name($model->alias) . '.' . $Db->name($this->settings[$model->alias]['field']) . '!= 1 AND ' . $queryData['conditions'];
				} else {
					$queryData['conditions'][$model->alias . '.' . $this->settings[$model->alias]['field'] . ' !='] = '1';
				}
			}
		}

		return $queryData;
	}

	/**
	 * Run before a model is saved, used to disable beforeFind() override.
	 *
	 * @param object $model Model about to be saved.
	 * @return boolean True if the operation should continue, false if it should abort
	 */
	public function beforeSave($model) {
		if ($this->settings[$model->alias]['find']) {
			if (!isset($this->__backAttributes)) {
				$this->__backAttributes = array($model->alias => array());
			} else if (!isset($this->__backAttributes[$model->alias])) {
				$this->__backAttributes[$model->alias] = array();
			}

			$this->__backAttributes[$model->alias]['find'] = $this->settings[$model->alias]['find'];
			$this->__backAttributes[$model->alias]['delete'] = $this->settings[$model->alias]['delete'];
			$this->enableSoftDeletable($model, false);
		}

		return true;
	}

	/**
	 * Run after a model has been saved, used to enable beforeFind() override.
	 *
	 * @param object $model Model just saved.
	 * @param boolean $created True if this save created a new record
	 */
	public function afterSave($model, $created) {
		if (isset($this->__backAttributes[$model->alias]['find'])) {
			$this->enableSoftDeletable($model, 'find', $this->__backAttributes[$model->alias]['find']);
			$this->enableSoftDeletable($model, 'delete', $this->__backAttributes[$model->alias]['delete']);
			unset($this->__backAttributes[$model->alias]['find']);
			unset($this->__backAttributes[$model->alias]['delete']);
		}
	}
}
?>
