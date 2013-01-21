<?php
/**
 * Lockable behavior
 *
 * @package Infinitas.Locks.Model.Behavior
 */

App::uses('InfinitasHelper', 'Libs.View/Helper');

/**
 * Lockable behavior
 *
 * This behavior auto binds to any model and will lock a row when it is being
 * edited by a user. only that user will be able to edit it while it is locked.
 * This will avoid any issues with many people working on content at the same
 * time
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Locks.Model.Behavior
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 * @author Ceeram
 */

class LockableBehavior extends ModelBehavior {
/**
 * Contain default settings.
 *
 * @var array
 */
	protected $_defaults = array();

/**
 * Constructor
 *
 * Clear out old locks when the model is loaded
 *
 * @return void
 */
	public function __construct() {
		parent::__construct();

		ClassRegistry::init('Locks.Lock')->clearOldLocks();
	}

/**
 * Behavior setup
 *
 * @param Model $Model Model using the behavior
 * @param array $config Settings to override for model.
 *
 * @return void
 */
	public function setup(Model $Model, $config = null) {
		if ($Model->alias == 'Lock' || $Model->Behaviors->enabled('Locks.Lockable')) {
			return;
		}

		$Model->bindModel(array(
			'hasOne' => array(
				'Lock' => array(
					'className' => 'Locks.Lock',
					'foreignKey' => 'foreign_key',
					'conditions' => array(
						'Lock.class' => $Model->plugin . '.' . $Model->alias
					),
					'fields' => array(
						'Lock.id',
						'Lock.created',
						'Lock.user_id'
					),
					'dependent' => true
				)
			)
		), false);

		if (is_array($config)) {
			$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
		} else {
			$this->settings[$Model->alias] = $this->_defaults;
		}
	}

/**
 * Locking rows.
 *
 * After a row has been pulled from the database this will record the locked
 * state with the user that locked it. if a user reads a row that they
 * locked the date will be updated. if a different user tries to read this
 * row nothing will be retured and the component will take over displaying
 * an error message
 *
 * @param Model $Model the current model
 * @param array $results the data that was found
 * @param boolean $primary is it the main model doing the find
 *
 * @return array
 */
	public function afterFind(Model $Model, $results, $primary) {
		$this->userId = AuthComponent::user('id');

		if (!$this->userId || $Model->findQueryType != 'first' || !$primary || empty($results)) {
			if (!$this->userId || $Model->findQueryType != 'all') {
				return $results;
			}

			foreach ($results as $k => &$result) {
				$result['Lock']['Locker'] = $result['Locker'];
				unset($result['Locker']);
			}

			return $results;
		}

		if (isset($results[0][$Model->alias][$Model->primaryKey])) {
			$Lock = ClassRegistry::init('Locks.Lock');
			$lock = $Lock->find('all', array(
				'conditions' => array(
					'Lock.foreign_key' => $results[0][$Model->alias][$Model->primaryKey],
					'Lock.class' => $Model->fullModelName()
				),
				'contain' => array(
					'Locker'
				)
			));

			if (isset($lock[0]['Lock']['user_id']) && $this->userId == $lock[0]['Lock']['user_id']) {
				$Lock->delete($lock[0]['Lock']['id']);
				$lock = array();
			}

			if (!empty($lock)) {
				return $lock;
			}

			$lock['Lock'] = array(
				'foreign_key' => $results[0][$Model->alias][$Model->primaryKey],
				'class' => $Model->fullModelName(),
				'user_id' => $this->userId
			);

			$Lock->create();
			$Lock->save($lock);
		}

		return $results;
	}

/**
 * contain the lock
 *
 * before a find is made the Lock model is added to contain so that
 * the lock details are available in the view to show if something is locked
 * or not.
 *
 * @param Model $Model referenced model
 * @param array $query the query being done
 *
 * @return array
 */
	public function beforeFind(Model $Model, $query) {
		if ($Model->findQueryType == 'count') {
			return $query;
		}

		if (!is_array($query['fields'])) {
			$query['fields'] = array($query['fields']);
		}

		$query['fields'] = array_merge((array)$query['fields'], array(
			$Model->Lock->alias . '.*',
			$Model->Lock->Locker->alias . '.id',
			$Model->Lock->Locker->alias . '.username',
		));

		$query['joins'][] = $Model->autoJoinModel($Model->Lock);
		$query['joins'][] = $Model->Lock->autoJoinModel($Model->Lock->Locker);

		return $query;
	}

/**
 * unlock after the save
 *
 * once the row has been saved, the lock can be removed so that other users
 * may have accesss to the data.
 *
 * @param Model $Model referenced model
 * @param bool $created if its a new row
 *
 * @return boolean
 */
	public function afterSave(Model $Model, $created) {
		if (!$created) {
			$this->_deleteLock($Model, $Model->data[$Model->alias][$Model->primaryKey]);
		}

		return parent::afterSave($Model, $created);
	}

/**
 * Unlock a row
 *
 * This method is for use when afterSave is not used eg: cancel pushed
 * or some other reason for manual unlock
 *
 * @param Model $Model the model being unlocked
 * @param string $id the id of the record being unlocked
 *
 * @return boolean
 */
	public function unlock(Model $Model, $id = null) {
		return $this->_deleteLock($Model, $id);
	}

/**
 * internal private method for deleting locks
 *
 * This will delete locks for records only when there is a user_id
 * in the session. It deletes based on model, pk and user_id.
 *
 * @param Model $Model the model being unlocked
 * @param string $id the id of the record being unlocked
 *
 * @return boolean
 */
	protected function _deleteLock(Model $Model, $id = null) {
		if (!AuthComponent::user('id') || !$id) {
			return true;
		}

		return $Model->Lock->deleteAll(array(
			$Model->Lock->alias . '.foreign_key' => $id,
			$Model->Lock->alias . '.class' => $Model->plugin . '.' . $Model->alias,
			$Model->Lock->alias . '.user_id' => AuthComponent::user('id')
		));
	}

}