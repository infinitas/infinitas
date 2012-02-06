<?php
	/**
	 * lockable behavior.
	 *
	 * This behavior auto binds to any model and will lock a row when it is being
	 * edited by a user. only that user will be able to edit it while it is locked.
	 * This will avoid any issues with many people working on content at the same
	 * time
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.locks
	 * @subpackage Infinitas.locks.behaviors.lockable
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 * @author Ceeram
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class LockableBehavior extends ModelBehavior {
		/**
		 * Contain default settings.
		 *
		 * @var array
		 * @access protected
		 */
		protected $_defaults = array(
		);

		/**
		 *
		 * @param object $Model Model using the behavior
		 * @param array $settings Settings to override for model.
		 * @access public
		 * @return void
		 */
		public function setup($Model, $config = null) {
			if($Model->alias == 'Lock' || !$Model->Behaviors->enabled('Locks.Lockable')){
				return;
			}

			$Model->bindModel(
				array(
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
				),
				false
			);

			if(rand(0, 50) == 0){
				$this->__gc($Model);
			}

			if (is_array($config)) {
				$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
			}

			else {
				$this->settings[$Model->alias] = $this->_defaults;
			}
		}

		/**
		 * Clear out old locks
		 *
		 * This will delete any locks that have expired (configured in Locks.timeout)
		 * so that things do not remain locked when its not needed.
		 */
		private function __gc($Model){
			ClassRegistry::init('Locks.Lock')->clearOldLocks();
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
		 * @var object $Model the current model
		 * @var array $results the data that was found
		 * @var bool $primary is it the main model doing the find
		 */
		public function afterFind($Model, $results, $primary){
			if($Model->findQueryType != 'first' || !$primary || empty($results)){
				if($Model->findQueryType != 'all') {
					return $results;
				}

				foreach($results as $k => &$result) {
					$result['Lock']['Locker'] = $result['LockLocker'];
					unset($result['LockLocker']);
				}

				return $results;
			}

			if(isset($results[0][$Model->alias][$Model->primaryKey])) {
				$Lock = ClassRegistry::init('Locks.Lock');

				$this->userId = CakeSession::read('Auth.User.id');
				$lock = $Lock->find(
					'all',
					array(
						'conditions' => array(
							'Lock.foreign_key' => $results[0][$Model->alias][$Model->primaryKey],
							'Lock.class' => $Model->fullModelName(),
							'Lock.created > ' => date('Y-m-d H:m:s', strtotime(Configure::read('Locks.timeout')))
						),
						'contain' => array(
							'Locker'
						)
					)
				);

				if(isset($lock[0]['Lock']['user_id']) && $this->userId == $lock[0]['Lock']['user_id']){
					$Lock->delete($lock[0]['Lock']['id']);
					$lock = array();
				}

				if(!empty($lock)){
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
		 * @param object $model referenced model
		 * @param array $query the query being done
		 *
		 * @return array the find query data
		 */
		public function beforeFind($Model, $query) {
			if($Model->findQueryType == 'count'){
				return $query;
			}

			if(!is_array($query['fields'])) {
				$query['fields'] = array($query['fields']);
			}


			$query['fields'] = array_merge(
				$query['fields'],
				array(
					'Lock.*',
					'LockLocker.id',
					'LockLocker.username',
				)
			);

			$query['joins'][] = array(
				'table' => 'global_locks',
				'alias' => 'Lock',
				'type' => 'LEFT',
				'conditions' => array(
					'Lock.class' => $Model->fullModelName(),
					'Lock.foreign_key = ' . $Model->alias . '.' . $Model->primaryKey,
				)
			);

			$query['joins'][] = array(
				'table' => 'core_users',
				'alias' => 'LockLocker',
				'type' => 'LEFT',
				'conditions' => array(
					'LockLocker.id = Lock.user_id',
				)
			);
			
			return $query;
		}

		/**
		 * unlock after the save
		 *
		 * once the row has been saved, the lock can be removed so that other users
		 * may have accesss to the data.
		 *
		 * @param object $model referenced model
		 * @param bool $created if its a new row
		 *
		 * @return bool true on succsess false if not.
		 */
		public function afterSave(&$Model, $created){
			if($created || !isset($Model->Lock) || !is_object($Model->Lock)){
				return parent::afterSave($Model, $created);
			}

			$Model->Lock->deleteAll(
				array(
					'Lock.foreign_key' => $Model->data[$Model->alias][$Model->primaryKey],
					'Lock.class' => $Model->plugin.'.'.$Model->alias,
					'Lock.user_id' => CakeSession::read('Auth.User.id')
				)
			);

			parent::afterSave($Model, $created);
		}
	}