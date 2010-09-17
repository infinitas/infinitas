<?php
	/**
	 * lockable behavior.
	 *
	 * When you use Model::lock() instead of Model::read() the record that is
	 * found will be locked to other users trying to edit the same record. there
	 * is also a unlock method for manual unlocking (when the user clicks cancel)
	 * and a automatic unlock for when they click save.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package libs
	 * @subpackage libs.models.behaviors.lockable
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
		var $_defaults = array(
		);

		/**
		*
		* @param object $Model Model using the behavior
		* @param array $settings Settings to override for model.
		* @access public
		* @return void
		*/
		function setup(&$Model, $config = null) {
			if($Model->alias == 'Lock'){
				return;
			}

			if (is_array($config)) {
				$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
			}

			else {
				$this->settings[$Model->alias] = $this->_defaults;
			}
		}

		private function __gc(&$Model){
			$Model->Lock->clearOldLocks();
		}

		public function afterFind(&$Model, $results, $primary){
			// just doing a count
			if(isset($results[0][0]['count'])){
				return $results;
			}

			if(rand(0, 10) == 0){
				$this->__gc($Model);
			}

			if(!empty($results) && $primary && count($results) == 1 && isset($results[0][$Model->alias][$Model->primaryKey])){
				$this->user_id = CakeSession::read('Auth.User.id');
				$lock = $Model->Lock->find(
					'all',
					array(
						'conditions' => array(
							'Lock.foreign_key' => $results[0][$Model->alias][$Model->primaryKey],
							'Lock.class' => $Model->plugin.'.'.$Model->alias,
							'Lock.created > ' => date('Y-m-d H:m:s', strtotime(Configure::read('Locks.timeout')))
						),
						'contain' => array(
							'Locker'
						)
					)
				);

				if(isset($lock[0]['Lock']['user_id']) && $this->user_id == $lock[0]['Lock']['user_id']){
					$Model->Lock->Behaviors->disable('Libs.Trashable');
					$Model->Lock->delete($lock[0]['Lock']['id']);
					$lock = false;
				}
				
				if(!empty($lock)){
					return $lock;
				}

				$lock['Lock'] = array(
					'foreign_key' => $results[0][$Model->alias][$Model->primaryKey],
					'class' => Inflector::camelize($Model->plugin).'.'.$Model->alias,
					'user_id' => $this->user_id
				);
				
				$Model->Lock->create();
				if($Model->Lock->save($lock)){
					return $results;
				}
			}
			
			return $results;
		}

		/**
		 * Lock a record.
		 *
		 * This is called instead of Model::read() and will attempt to lock the record
		 * to other users that want to edit it, avoiding saving over eachothers work.
		 *
		 * @param object $model referenced model
		 * @param array $fields the fields that should be returned
		 * @param int $id the id of the record to unlock
		 *
		 * @return mixed false if already locked, array of data if not.
		 */
		function beforeFind(&$Model, $query) {
			$query['contain'][$Model->Lock->alias] = array('Locker');
			call_user_func(array($Model, 'contain'), $query['contain']);
			return $query;
		}

		/**
		* This function needs to be called manualy when you are editing an item but
		* decide to cancel, so that the record can be unlocked again.  Hitting back
		* on the browser will leave the file locked and others will not be able to
		* access it for editing.
		*
		* @param object $model referenced model
		* @param int $id the id of the record to unlock
		*
		* @return bool true on succsess false if not.
		*/
		function afterSave(&$Model, $created){
			if($created){
				return parent::afterSave(&$Model, $created);
			}

			$Model->Lock->deleteAll(
				array(
					'Lock.foreign_key' => $Model->data[$Model->alias][$Model->primaryKey],
					'Lock.class' => $Model->plugin.'.'.$Model->alias,
					'Lock.user_id' => CakeSession::read('Auth.User.id')
				)
			);

			parent::afterSave(&$Model, $created);
		}

		/**
		* Before saving set fields to unlock the record.
		*/
		function beforeSave($Model) {
			$Model->data[$Model->name]['locked'] = 0;
			$Model->data[$Model->name]['locked_by'] = null;
			$Model->data[$Model->name]['locked_since'] = null;
			return true;
		}
	}