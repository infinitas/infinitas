<?php
	/**
	 * SequenceBehavior maintains a contiguous sequence of integers (starting at 0
	 * or other configurable integer) in a selected column, such as `order`, for all
	 * records in a table, or groups of records identified by one or more
	 * 'groupFields', when adding, editing (including moving groups) or deleting
	 * records.
	 *
	 * Consider the following simple example with no groups:
	 * Record  Order
	 * A	   0
	 * B	   1
	 * C	   2
	 * D	   3
	 * E	   4
	 * F	   5
	 * G	   6
	 *   - Save
	 * 	 - If adding new record
	 * 	   - If order not specified e.g. Record H added:
	 * 		   Inserts H at end of list i.e. highest order + 1
	 * 	   - If order specified e.g. Record H added at position 3:
	 * 		   Inserts at specified order
	 * 		   Increments order of all other records whose order >= order of
	 * 			inserted record i.e. D, E, F & G get incremented
	 * 	 - If editing existing record:
	 * 	   - If order not specified and group not specified, or same
	 * 		   No Action
	 * 	   - If order not specified but group specified and different:
	 * 		   Decrement order of all records whose order > old order in the old
	 * 			group, and change order to highest order of new groups + 1
	 * 	   - If order specified:
	 * 		 - If new order < old order e.g. record E moves from 4 to 2
	 * 			 Increments order of all other records whose order >= new order and
	 * 			  order < old order i.e. order of C & D get incremented
	 * 		 - If new order > old order e.g. record C moves from 2 to 4
	 * 			 Decrements order of all other records whose order > old order and
	 * 			  <= new order i.e. order of D & E get decremented
	 * 		 - If new order == old order
	 * 			 No action
	 *   - Delete
	 * 	   Decrement order of all records whose order > order of deleted record
	 *
	 * @author Neil Crookes <neil@neilcrookes.com>
	 * @link http://www.neilcrookes.com
	 * @copyright (c) 2010 Neil Crookes
	 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
	 * @link http://www.neilcrookes.com
	 * @package cake
	 * @subpackage cake.base
	 */
	class SequenceBehavior extends ModelBehavior {
		/**
		 * Default settings for a model that has this behavior attached.
		 *
		 * @var array
		 * @access protected
		 */
		protected $_defaults = array(
			'orderField' => 'ordering',
			'groupFields' => false,
			'startAt' => 1,
		);

		/**
		 * Stores the current order of the record
		 *
		 * @var integer
		 * @access protected
		 */
		protected $_oldOrder;

		/**
		 * Stores the new order of the record
		 *
		 * @var integer
		 * @access protected
		 */
		protected $_newOrder;

		/**
		 * Stores the current values of the group fields for the record, before it's
		 * saved or deleted, retrieved from the database
		 *
		 * @var array
		 * @access protected
		 */
		protected $_oldGroups;

		/**
		 * Stores the new values of the group fields for the record, before it's saved
		 * or deleted, retrieved from the model->data
		 *
		 * @var array
		 * @access protected
		 */
		protected $_newGroups;

		/**
		 * Stores the update instructions with keys for update, which is the actual
		 * <field> => <field> +/- 1 part, and for conditions, which identify the
		 * records to which the update will be applied, and optional group_values for
		 * the case where you are editing a record, and the groups are changing and
		 * the new order in the new group is specified.
		 *
		 * @var array
		 * @access protected
		 */
		protected $_update;
		
		/**
		 * @brief internal cache of the settings per model
		 * 
		 * @access private
		 * @var array
		 */
		private $__settings = array();

		/**
		 * Merges the passed config array defined in the model's actsAs property with
		 * the behavior's defaults and stores the resultant array in this->__settings
		 * for the current model.
		 *
		 * @access public
		 *
		 * @param Model $Model Model object that method is triggered on
		 * @param array $config Configuration options include:
		 * - orderField - The database field name that stores the sequence number.
		 * 	Default is order.
		 * - groupFields - Array of database field names that identify a single
		 * 	group of records that need to form a contiguous sequence. Default is
		 * 	false, i.e. no group fields
		 * - startAt - You can start your sequence numbers at 0 or 1 or any other.
		 * 	Default is 0
		 *
		 * @return void
		 */
		public function setup($Model, $config = array()) {
			if (is_string($config)) {
				$config = array('orderField' => $config);
			}

			$this->__settings[$Model->alias] = array_merge($this->_defaults, $config);
			$this->__settings[$Model->alias]['escaped_orderField'] = $Model->escapeField($this->__settings[$Model->alias]['orderField']);
			
			if (!empty($this->__settings[$Model->alias]['groupFields'])) {
				if (is_string($this->__settings[$Model->alias]['groupFields'])) {
					$this->__settings[$Model->alias]['groupFields'] = array($this->__settings[$Model->alias]['groupFields']);
				}
				
				$this->__settings[$Model->alias]['groupFields'] = array_flip($this->__settings[$Model->alias]['groupFields']);
				foreach ($this->__settings[$Model->alias]['groupFields'] as $groupField => $null) {
					$this->__settings[$Model->alias]['groupFields'][$groupField] = $Model->escapeField($groupField);
				}
			}
		}

		/**
		 * Adds order value if not already set in query data
		 *
		 * @access public
		 *
		 * @param Model $Model Model object that method is triggered on
		 * @param array $queryData Original queryData
		 *
		 * @return array Modified queryData
		 */
		public function beforeFind($Model, $queryData) {
			// order can can sometimes be not set, or empty, or array(0 => null)
			$check = !isset($queryData['order']) ||
				empty($queryData['order']) || 
				(is_array($queryData['order']) && count($queryData['order']) == 1 && empty($queryData['order'][0]));
			
			if ($check) {
				$queryData['order'] = (array)$this->__settings[$Model->alias]['escaped_orderField'];
			}

			return $queryData;
		}

		/**
		 * Sets update actions and their conditions which get executed in after save,
		 * affects model->data when necessary
		 *
		 * @access public
		 *
		 * @param Model $Model Model object that method is triggered on
		 *
		 * @return boolean Always true otherwise model will not save
		 */
		public function beforeSave($Model) {
			$this->_update[$Model->alias] = array();
			
			$this->_setNewOrder($Model);
			$this->_setNewGroups($Model);

			$orderField = $this->__settings[$Model->alias]['orderField'];
			
			$highestPossible = $this->getHighestOrder($Model, $this->_newGroups[$Model->alias]);

			if (!$Model->id) {
				$highestPossible++;
				$this->_beforeSaveCreate($Model, $highestPossible);
			}
			else{
				$this->_beforeSaveUpdate($Model, $highestPossible);
			}

			return true;
		}

		/**
		 * @brief ordering for new rows
		 *
		 * This method works out what needs to be done for new rows, called in beforeSave()
		 *
		 * @access protected
		 * 
		 * @param Model $Model Model object that method is triggered on
		 * @param int $highestPossible the highest possible value for the order field
		 *
		 * @return void
		 */
		protected function _beforeSaveCreate($Model, $highestPossible) {
			$orderField = $this->__settings[$Model->alias]['orderField'];
			
			if(isset($Model->data[$Model->alias][$orderField]) && $Model->data[$Model->alias][$orderField] > $highestPossible) {
				$Model->data[$Model->alias][$orderField] = $highestPossible;
			}

			if (!isset($this->_newOrder[$Model->alias])) {
				$Model->data[$Model->alias][$orderField] = $highestPossible;
				return true;
			}

			$this->_oldGroups[$Model->alias] = $this->_newGroups[$Model->alias];
			$this->_update[$Model->alias][] = array(
				'action' => array(
					$this->__settings[$Model->alias]['escaped_orderField'] => $this->__settings[$Model->alias]['escaped_orderField'] . ' + 1'
				),
				'conditions' => array(
					$this->__settings[$Model->alias]['escaped_orderField'] . ' >=' => $this->_newOrder[$Model->alias]
				),
			);

			return true;
		}

		/**
		 * @brief ordering for new rows
		 *
		 * This method works out what needs to be done for new rows, called in beforeSave()
		 *
		 * @access protected
		 *
		 * @param Model $Model Model object that method is triggered on
		 * @param int $highestPossible the highest possible value for the order field
		 *
		 * @return void
		 */
		protected function _beforeSaveUpdate($Model, $highestPossible) {
			$orderField = $this->__settings[$Model->alias]['orderField'];
			
			if(!empty($this->_newGroups[$Model->alias])) {
				$highestPossible++;
			}
			
			if(isset($Model->data[$Model->alias][$orderField]) && $Model->data[$Model->alias][$orderField] > $highestPossible) {
				$Model->data[$Model->alias][$orderField] = $highestPossible;
			}
			
			$this->_setOldOrder($Model);
			$this->_setOldGroups($Model);
	  
			if (!isset($this->_newOrder[$Model->alias]) && !isset($this->_newGroups[$Model->alias])) {
				return true;
			}

			// No action if new and old group and order same
			if (($this->_newOrder[$Model->alias] == $this->_oldOrder[$Model->alias]) && ($this->_newGroups[$Model->alias] == $this->_oldGroups[$Model->alias])) {
				return true;
			}

			// If changing group
			if ($this->_newGroups[$Model->alias] && ($this->_newGroups[$Model->alias] != $this->_oldGroups[$Model->alias])) {
				// Decrement records in old group with higher order than moved record old order
				$this->_update[$Model->alias][] = array(
					'action' => array(
						$this->__settings[$Model->alias]['escaped_orderField'] => $this->__settings[$Model->alias]['escaped_orderField'] . ' - 1'
					),
					'conditions' => array(
						$this->__settings[$Model->alias]['escaped_orderField'] . ' >=' => $this->_oldOrder[$Model->alias]
					),
				);

				if (!isset($this->_newOrder[$Model->alias])) {
					$Model->data[$Model->alias][$orderField] = $highestPossible;
				}

				else {
					// Increment records in new group with higher order than moved record new order
					$this->_update[$Model->alias][] = array(
						'action' => array(
							$this->__settings[$Model->alias]['escaped_orderField'] => $this->__settings[$Model->alias]['escaped_orderField'] . ' + 1'
						),
						'conditions' => array(
							$this->__settings[$Model->alias]['escaped_orderField'] . ' >=' => $this->_newOrder[$Model->alias],
						),
						'group_values' => $this->_newGroups[$Model->alias],
					);
				}
				// Same group
			}

			else {
				// If moving up
				if ($this->_newOrder[$Model->alias] < $this->_oldOrder[$Model->alias]) {
					// Increment order of those in between
					$this->_update[$Model->alias][] = array(
						'action' => array(
							$this->__settings[$Model->alias]['escaped_orderField'] => $this->__settings[$Model->alias]['escaped_orderField'] . ' + 1'
						),
						'conditions' => array(
							array($this->__settings[$Model->alias]['escaped_orderField'] . ' >=' => $this->_newOrder[$Model->alias]),
							array($this->__settings[$Model->alias]['escaped_orderField'] . ' <' => $this->_oldOrder[$Model->alias]),
						),
					);
					// Moving down
				}

				else {
					// Decrement order of those in between
					$this->_update[$Model->alias][] = array(
						'action' => array(
							$this->__settings[$Model->alias]['escaped_orderField'] => $this->__settings[$Model->alias]['escaped_orderField'] . ' - 1'
						),
						'conditions' => array(
							array($this->__settings[$Model->alias]['escaped_orderField'] . ' >' => $this->_oldOrder[$Model->alias]),
							array($this->__settings[$Model->alias]['escaped_orderField'] . ' <=' => $this->_newOrder[$Model->alias]),
						),
					);
				}
			}
			
			return true;
		}

		/**
		 * Called automatically after model gets saved, triggers order updates
		 *
		 * @access public
		 *
		 * @param Model $Model Model object that method is triggered on
		 * @param boolean $created Whether the record was created or not
		 *
		 * @return boolean
		 */
		public function afterSave($Model, $created) {
			return $this->_updateAll($Model);
		}

		/**
		 * When you delete a record from a set, you need to decrement the order of all
		 * records that were after it in the set.
		 *
		 * @access public
		 *
		 * @param Model $Model Model object that method is triggered on
		 *
		 * @return boolean Always true
		 */
		public function beforeDelete($Model) {
			$this->_update[$Model->alias] = array();
			
			$this->_setOldOrder($Model);
			$this->_setOldGroups($Model);

			$escapedOrderField = $this->__settings[$Model->alias]['escaped_orderField'];

			$this->_update[$Model->alias][] = array(
				'action' => array($escapedOrderField => $escapedOrderField . ' - 1'),
				'conditions' => array($escapedOrderField . ' >' => $this->_oldOrder[$Model->alias]),
			);

			return true;
		}

		/**
		 * Called automatically after model gets deleted, triggers order updates
		 *
		 * @access public
		 *
		 * @param Model $Model Model object that method is triggered on
		 * 
		 * @return boolean
		 */
		public function afterDelete($Model) {
			return $this->_updateAll($Model);
		}

		/**
		 * Returns the current highest order of all records in the set. When a new
		 * record is added to the set, it is added at the current highest order, plus
		 * one.
		 *
		 * @access public
		 *
		 * @param Model $Model Model object that method is triggered on
		 * @param array $groupValues Array with group field => group values, used for conditions
		 *
		 * @return integer Value of order field of last record in set
		 */
		public function getHighestOrder($Model, $groupValues = false) {	
			$count = $Model->find(
				'count',
				array(
					'conditions' => $this->_conditionsForGroups($Model, $groupValues),
					'contain' => false
				)
			);
			
			// If there is a last record (i.e. any) in the set, return the it's order
			if ($count) {
				return $count;
			}
			// If there isn't any records in the set, return the start number minus 1
			return (int)$this->__settings[$Model->alias]['startAt'] - 1;
		}

		/**
		 * If editing or deleting a record, set the oldOrder property to the current
		 * order in the database.
		 * @access protected
		 *
		 * @param Model $Model Model object that method is triggered on
		 * 
		 * @return void
		 */
		protected function _setOldOrder($Model) {
			$this->_oldOrder[$Model->alias] = null;

			$orderField = $this->__settings[$Model->alias]['orderField'];
			// Set old order to record's current order in database
			$this->_oldOrder[$Model->alias] = $Model->field($orderField);
		}

		/**
		 * If editing or deleting a record, set the oldGroups property to the current
		 * group values in the database for each group field for this model.
		 *
		 * @access protected
		 *
		 * @param Model $Model Model object that method is triggered on
		 *
		 * @return void
		 */
		protected function _setOldGroups($Model) {
			$this->_oldGroups[$Model->alias] = null;

			$groupFields = $this->__settings[$Model->alias]['groupFields'];
			// If this model does not have any groups, return
			if ($groupFields === false) {
				return;
			}
			// Set oldGroups property with key of group field and current value of group
			// field from db
			foreach ($groupFields as $groupField => $escapedGroupField) {
				$this->_oldGroups[$Model->alias][$groupField] = $Model->field($groupField);
			}
		}

		/**
		 * Sets new order property for current model to value in model->data
		 *
		 * @access protected
		 *
		 * @param Model $Model Model object that method is triggered on
		 *
		 * @return void
		 */
		protected function _setNewOrder($Model) {
			$this->_newOrder[$Model->alias] = null;

			$orderField = $this->__settings[$Model->alias]['orderField'];
			$startAt = $this->__settings[$Model->alias]['startAt'];
			
			if (!isset($Model->data[$Model->alias][$orderField]) || $Model->data[$Model->alias][$orderField] < $startAt) {
				return;
			}
			
			$this->_newOrder[$Model->alias] = $Model->data[$Model->alias][$orderField];

		}

		/**
		 * Set new groups property with keys of group field and values from
		 * $Model->data, if set.
		 *
		 * @access protected
		 *
		 * @param Model $Model Model object that method is triggered on
		 * 
		 * @return void
		 */
		protected function _setNewGroups($Model) {
			$this->_newGroups[$Model->alias] = null;

			if ($this->__settings[$Model->alias]['groupFields'] === false) {
				return;
			}

			foreach(array_keys($Model->data[$Model->alias]) as $key) {
				$escapedField = $Model->escapeField($key);
				
				if(in_array($escapedField, $this->__settings[$Model->alias]['groupFields'])) {
					$this->_newGroups[$Model->alias][$escapedField] = $Model->data[$Model->alias][$key];
				}
			}
		}

		/**
		 * Returns array of conditions for restricting a record set according to the
		 * model's group fields setting.
		 *
		 * @access protected
		 *
		 * @param Model $Model Model object that method is triggered on
		 * @param array $groupValues Array of group field => group value pairs
		 * 
		 * @return array Array of escaped group field => group value pairs
		 */
		protected function _conditionsForGroups($Model, $groupValues = false) {
			if ($this->__settings[$Model->alias]['groupFields'] === false) {
				return array();
			}
			
			$groupValues = ($groupValues !== false) ? $groupValues : $this->_oldGroups[$Model->alias];

			$conditions = array();
			foreach($this->__settings[$Model->alias]['groupFields'] as $groupField => $escapedGroupField) {
				$groupValue = null;
				if (isset($groupValues[$escapedGroupField])) {
					$groupValue = $groupValues[$escapedGroupField];
				}
				else if(isset($groupValues[$groupField])){
					$groupValue = $groupValues[$groupField];
				}

				$conditions[] = array($escapedGroupField => $groupValue);
			}
			
			return $conditions;
		}

		/**
		 * When doing any update all calls, you want to avoid updating the record
		 * you've just modified, as the order will have been set already, so exclude
		 * it with some conditions.
		 *
		 * @access protected
		 *
		 * @param Model $Model Model object that method is triggered on
		 * 
		 * @return array Array Model.primary_key <> => $id
		 */
		protected function _conditionsNotCurrent($Model) {
			return array($Model->escapeField($Model->primaryKey) . ' <>' => $Model->id);
		}

		/**
		 * Executes the update, if there are any. Called in afterSave and afterDelete.
		 *
		 * @access protected
		 *
		 * @param Model $Model Model object that method is triggered on
		 * 
		 * @return boolean
		 */
		protected function _updateAll($Model) {
			if (empty($this->_update[$Model->alias])) {
				return true;
			}

			$return = true;
			foreach ($this->_update[$Model->alias] as $update) {
				$groupValues = false;

				if (isset($update['group_values'])) {
					$groupValues = $update['group_values'];
				}
				
				// Actual conditions for the update are a combination of what's derived in
				// the beforeSave or beforeDelete, and conditions to not the record we've
				// just modified/inserted and conditions to make sure only records in the
				// current record's groups
				$conditions = array_merge(
					$this->_conditionsForGroups($Model, $groupValues),
					$this->_conditionsNotCurrent($Model),
					$update['conditions']
				);

				$return = $return && $Model->updateAll($update['action'], $conditions);
			}

			return $return;
		}
	}