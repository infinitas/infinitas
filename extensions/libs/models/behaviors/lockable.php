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
			'fields' => array(
				'locked_by' => 'locked_by',
				'locked_since' => 'locked_since',
				'locked' => 'locked',
				'modified' => 'modified'
				)
			);

		/**
		*
		* @param object $Model Model using the behavior
		* @param array $settings Settings to override for model.
		* @access public
		* @return void
		*/
		function setup(&$Model, $config = null) {
			if (is_array($config)) {
				$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
			} else {
				$this->settings[$Model->alias] = $this->_defaults;
			}
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
		function lock(&$Model, $fields = null, $id = null) {
			$Model->contain();

			if($data = $Model->read($this->settings[$Model->alias]['fields'], $id) == false) {
				return false;
			}

			$this->Session = new CakeSession();
			$user_id = $this->Session->read('Auth.User.id');
			if($data[$Model->alias]['locked'] && $data[$Model->alias]['locked_by'] != $user_id) {
				return false;
			}
			
			$data[$Model->alias] = array(
				'id' => $id,
				$this->settings[$Model->alias]['fields']['locked'] => 1,
				$this->settings[$Model->alias]['fields']['locked_by'] => $user_id,
				$this->settings[$Model->alias]['fields']['locked_since'] => date('Y-m-d H:i:s'),
				$this->settings[$Model->alias]['fields']['modified'] => false
			);
			$Model->save($data, array('validate' => false, 'callbacks' => false));
			$data = $Model->read($fields, $id);
			return $data;
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
		function unlock(&$Model, $id = null){
			$Model->contain();

			if($data = $Model->read($this->settings[$Model->alias]['fields'], $id) == false) {
				return false;
			}

			$data[$Model->alias] = array(
				'id' => $id,
				$this->settings[$Model->alias]['fields']['locked'] => 0,
				$this->settings[$Model->alias]['fields']['locked_by'] => null,
				$this->settings[$Model->alias]['fields']['locked_since'] => null,
				$this->settings[$Model->alias]['fields']['modified'] => false
			);
			return $Model->save($data, array('validate' => false, 'callbacks' => false));
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
?>