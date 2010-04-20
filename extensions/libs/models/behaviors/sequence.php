<?php
	/**
	 * SequenceBehavior maintains a contiguous sequence of integers (starting at 0
	 * or other configurable integer) in a selected column, such as `order`, for all
	 * records in a table, or groups of records identified by one or more
	 * 'group_fields', when adding, editing (including moving groups) or deleting
	 * records.
	 *
	 * Consider the following simple example with no groups:
	 * Record  Order
	 * A       0
	 * B       1
	 * C       2
	 * D       3
	 * E       4
	 * F       5
	 * G       6
	 *   - Save
	 *     - If adding new record
	 *       - If order not specified e.g. Record H added:
	 *           Inserts H at end of list i.e. highest order + 1
	 *       - If order specified e.g. Record H added at position 3:
	 *           Inserts at specified order
	 *           Increments order of all other records whose order >= order of
	 *            inserted record i.e. D, E, F & G get incremented
	 *     - If editing existing record:
	 *       - If order not specified and group not specified, or same
	 *           No Action
	 *       - If order not specified but group specified and different:
	 *           Decrement order of all records whose order > old order in the old
	 *            group, and change order to highest order of new groups + 1
	 *       - If order specified:
	 *         - If new order < old order e.g. record E moves from 4 to 2
	 *             Increments order of all other records whose order >= new order and
	 *              order < old order i.e. order of C & D get incremented
	 *         - If new order > old order e.g. record C moves from 2 to 4
	 *             Decrements order of all other records whose order > old order and
	 *              <= new order i.e. order of D & E get decremented
	 *         - If new order == old order
	 *             No action
	 *   - Delete
	 *       Decrement order of all records whose order > order of deleted record
	 *
	 * @author Neil Crookes <neil@neilcrookes.com>
	 * @link http://www.neilcrookes.com
	 * @copyright (c) 2010 Neil Crookes
	 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
	 * @link http://www.neilcrookes.com
	 * @package cake
	 * @subpackage cake.base
	 */

	class SequenceBehavior extends ModelBehavior
	{
		/**
		 * Default settings for a model that has this behavior attached.
		 *
		 * @var array
		 * @access protected
		 */
		protected $_defaults = array(
			'order_field' => 'ordering',
			'group_fields' => false,
			'start_at' => 1,
		);

		/**
		 * Stores the current order of the record
		 *
		 * @var integer
		 */
		protected $_oldOrder;

		/**
		 * Stores the new order of the record
		 *
		 * @var integer
		 */
		protected $_newOrder;

		/**
		 * Stores the current values of the group fields for the record, before it's
		 * saved or deleted, retrieved from the database
		 *
		 * @var array
		 */
		protected $_oldGroups;

		/**
		 * Stores the new values of the group fields for the record, before it's saved
		 * or deleted, retrieved from the model->data
		 *
		 * @var array
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
		 */
		protected $_update;

		/**
		 * Merges the passed config array defined in the model's actsAs property with
		 * the behavior's defaults and stores the resultant array in this->settings
		 * for the current model.
		 *
		 * @param Model $model Model object that method is triggered on
		 * @param array $config Configuration options include:
		 * - order_field - The database field name that stores the sequence number.
		 *    Default is order.
		 * - group_fields - Array of database field names that identify a single
		 *    group of records that need to form a contiguous sequence. Default is
		 *    false, i.e. no group fields
		 * - start_at - You can start your sequence numbers at 0 or 1 or any other.
		 *    Default is 0
		 * @return void
		 */
		public function setup(&$model, $config = array())
		{
			// If config is a string, assume it's the order field
			if (is_string($config))
			{
				$config = array('order_field' => $config);
			}
			// Merge defaults with passed config and put in settings for model
			$this->settings[$model->alias] = array_merge($this->_defaults, $config);
			// Set the escaped order field setting
			$this->settings[$model->alias]['escaped_order_field'] = $model->escapeField($this->settings[$model->alias]['order_field']);
			// If group_fields in settings is false, return now as remainder not needed
			if ($this->settings[$model->alias]['group_fields'] === false)
			{
				return;
			}
			// If group_fields in settings is a string, make it an array
			if (is_string($this->settings[$model->alias]['group_fields']))
			{
				$this->settings[$model->alias]['group_fields'] = array($this->settings[$model->alias]['group_fields']);
			}
			// Set the group fields as the keys so we can add the escaped version as the
			// values
			$this->settings[$model->alias]['group_fields'] = array_flip($this->settings[$model->alias]['group_fields']);
			foreach ($this->settings[$model->alias]['group_fields'] as $groupField => $null)
			{
				$this->settings[$model->alias]['group_fields'][$groupField] = $model->escapeField($groupField);
			}
		}

		/**
		 * Adds order value if not already set in query data
		 *
		 * @param Model $model Model object that method is triggered on
		 * @param array $queryData Original queryData
		 * @return array Modified queryData
		 */
		public function beforeFind(&$model, $queryData)
		{
			// order can can sometimes be not set, or empty, or array(0 => null)
			if (!isset($queryData['order']) || empty($queryData['order']) || (is_array($queryData['order']) && count($queryData['order']) == 1 && empty($queryData['order'][0])))
			{
				$queryData['order'] = $this->settings[$model->alias]['escaped_order_field'];
			}

			return $queryData;
		}

		/**
		 * Sets update actions and their conditions which get executed in after save,
		 * affects model->data when necessary
		 *
		 * @param Model $model Model object that method is triggered on
		 * @return boolean Always true otherwise model will not save
		 */
		public function beforeSave(&$model)
		{
			$this->_update[$model->alias] = array();
			// Sets new order and new groups from model->data
			$this->_setNewOrder($model);
			$this->_setNewGroups($model);

			$orderField = $this->settings[$model->alias]['order_field'];
			$escapedOrderField = $this->settings[$model->alias]['escaped_order_field'];
			// Adding
			if (!$model->id)
			{
				// Order not specified
				if (!isset($this->_newOrder[$model->alias]))
				{
					// Insert at end of list
					$model->data[$model->alias][$orderField] = $this->_getHighestOrder($model, $this->_newGroups[$model->alias]) + 1;
					// Order specified
				}
				else
				{
					// The updateAll called in afterSave uses old groups values as default
					// conditions to restrict which records are updated, so set old groups
					// to new groups as old isn't set.
					$this->_oldGroups[$model->alias] = $this->_newGroups[$model->alias];
					// Insert and increment order of records it's inserted before
					$this->_update[$model->alias][] = array(
						'action' => array($escapedOrderField => $escapedOrderField . ' + 1'
						),
						'conditions' => array($escapedOrderField . ' >=' => $this->_newOrder[$model->alias]
						),
					);
				}
				// Editing
			}
			else
			{
				// No action if no new order or group specified
				if (!isset($this->_newOrder[$model->alias]) && !isset($this->_newGroups[$model->alias]))
				{
					return;
				}

				$this->_setOldOrder($model);
				$this->_setOldGroups($model);
				// No action if new and old group and order same
				if ($this->_newOrder[$model->alias] == $this->_oldOrder[$model->alias] && ($this->_newGroups[$model->alias] == $this->_oldGroups[$model->alias]))
				{
					return;
				}
				// If changing group
				if ($this->_newGroups[$model->alias] && ($this->_newGroups[$model->alias] != $this->_oldGroups[$model->alias]))
				{
					// Decrement records in old group with higher order than moved record old order
					$this->_update[$model->alias][] = array(
						'action' => array($escapedOrderField => $escapedOrderField . ' - 1'
							),
						'conditions' => array($escapedOrderField . ' >=' => $this->_oldOrder[$model->alias],
							),
						);
					// Order not specified
					if (!isset($this->_newOrder[$model->alias]))
					{
						// Insert at end of new group
						$model->data[$model->alias][$orderField] = $this->_getHighestOrder($model, $this->_newGroups[$model->alias]) + 1;
						// Order specified
					}
					else
					{
						// Increment records in new group with higher order than moved record new order
						$this->_update[$model->alias][] = array(
							'action' => array($escapedOrderField => $escapedOrderField . ' + 1'
								),
							'conditions' => array($escapedOrderField . ' >=' => $this->_newOrder[$model->alias],
								),
							'group_values' => $this->_newGroups[$model->alias],
							);
					}
					// Same group
				}
				else
				{
					// If moving up
					if ($this->_newOrder[$model->alias] < $this->_oldOrder[$model->alias])
					{
						// Increment order of those in between
						$this->_update[$model->alias][] = array(
							'action' => array($escapedOrderField => $escapedOrderField . ' + 1'
								),
							'conditions' => array(
								array($escapedOrderField . ' >=' => $this->_newOrder[$model->alias]),
								array($escapedOrderField . ' <' => $this->_oldOrder[$model->alias]),
								),
							);
						// Moving down
					}
					else
					{
						// Decrement order of those in between
						$this->_update[$model->alias][] = array(
							'action' => array($escapedOrderField => $escapedOrderField . ' - 1'
								),
							'conditions' => array(
								array($escapedOrderField . ' >' => $this->_oldOrder[$model->alias]),
								array($escapedOrderField . ' <=' => $this->_newOrder[$model->alias]),
								),
							);
					}
				}
			}

			return true;
		}

		/**
		 * Called automatically after model gets saved, triggers order updates
		 *
		 * @param Model $model Model object that method is triggered on
		 * @param boolean $created Whether the record was created or not
		 * @return boolean
		 */
		public function afterSave(&$model, $created)
		{
			return $this->_updateAll($model);
		}

		/**
		 * When you delete a record from a set, you need to decrement the order of all
		 * records that were after it in the set.
		 *
		 * @param Model $model Model object that method is triggered on
		 * @return boolean Always true
		 */
		public function beforeDelete(&$model)
		{
			$this->_update[$model->alias] = array();
			// Set current order and groups of record to be deleted
			$this->_setOldOrder($model);
			$this->_setOldGroups($model);

			$escapedOrderField = $this->settings[$model->alias]['escaped_order_field'];
			// Decrement records in group with higher order than deleted record
			$this->_update[$model->alias][] = array(
				'action' => array($escapedOrderField => $escapedOrderField . ' - 1'
					),
				'conditions' => array($escapedOrderField . ' >' => $this->_oldOrder[$model->alias],
					),
				);

			return true;
		}

		/**
		 * Called automatically after model gets deleted, triggers order updates
		 *
		 * @param Model $model Model object that method is triggered on
		 * @return boolean
		 */
		public function afterDelete(&$model)
		{
			return $this->_updateAll($model);
		}

		/**
		 * Returns the current highest order of all records in the set. When a new
		 * record is added to the set, it is added at the current highest order, plus
		 * one.
		 *
		 * @param Model $model Model object that method is triggered on
		 * @param array $groupValues Array with group field => group values, used for conditions
		 * @return integer Value of order field of last record in set
		 */
		protected function _getHighestOrder(&$model, $groupValues = false)
		{
			$orderField = $this->settings[$model->alias]['order_field'];
			$escapedOrderField = $this->settings[$model->alias]['escaped_order_field'];
			// Conditions for the record set to which this record will be added to.
			$conditions = $this->_conditionsForGroups($model, $groupValues);
			// Find the last record in the set
			$last = $model->find('first', array(
					'conditions' => $conditions,
					'recursive' => - 1,
					'order' => $escapedOrderField . ' DESC',
					));
			// If there is a last record (i.e. any) in the set, return the it's order
			if ($last)
			{
				return $last[$model->alias][$orderField];
			}
			// If there isn't any records in the set, return the start number minus 1
			return ((int)$this->settings[$model->alias]['start_at'] - 1);
		}

		/**
		 * If editing or deleting a record, set the oldOrder property to the current
		 * order in the database.
		 *
		 * @param Model $model Model object that method is triggered on
		 * @return void
		 */
		protected function _setOldOrder(&$model)
		{
			$this->_oldOrder[$model->alias] = null;

			$orderField = $this->settings[$model->alias]['order_field'];
			// Set old order to record's current order in database
			$this->_oldOrder[$model->alias] = $model->field($orderField);
		}

		/**
		 * If editing or deleting a record, set the oldGroups property to the current
		 * group values in the database for each group field for this model.
		 *
		 * @param Model $model Model object that method is triggered on
		 * @return void
		 */
		protected function _setOldGroups(&$model)
		{
			$this->_oldGroups[$model->alias] = null;

			$groupFields = $this->settings[$model->alias]['group_fields'];
			// If this model does not have any groups, return
			if ($groupFields === false)
			{
				return;
			}
			// Set oldGroups property with key of group field and current value of group
			// field from db
			foreach ($groupFields as $groupField => $escapedGroupField)
			{
				$this->_oldGroups[$model->alias][$groupField] = $model->field($groupField);
			}
		}

		/**
		 * Sets new order property for current model to value in model->data
		 *
		 * @param Model $model Model object that method is triggered on
		 * @return void
		 */
		protected function _setNewOrder(&$model)
		{
			$this->_newOrder[$model->alias] = null;

			extract($this->settings[$model->alias]);

			if (!isset($model->data[$model->alias][$order_field]) || $model->data[$model->alias][$order_field] < $start_at)
			{
				return;
			}

			$this->_newOrder[$model->alias] = $model->data[$model->alias][$order_field];
		}

		/**
		 * Set new groups property with keys of group field and values from
		 * $model->data, if set.
		 *
		 * @param Model $model Model object that method is triggered on
		 * @return void
		 */
		protected function _setNewGroups(&$model)
		{
			$this->_newGroups[$model->alias] = null;

			$groupFields = $this->settings[$model->alias]['group_fields'];
			// Return if this model has not group fields
			if ($groupFields === false)
			{
				return;
			}

			foreach ($groupFields as $groupField => $escapedGroupField)
			{
				if (isset($model->data[$model->alias][$groupField]))
				{
					$this->_newGroups[$model->alias][$groupField] = $model->data[$model->alias][$groupField];
				}
			}
		}

		/**
		 * Returns array of conditions for restricting a record set according to the
		 * model's group fields setting.
		 *
		 * @param Model $model Model object that method is triggered on
		 * @param array $groupValues Array of group field => group value pairs
		 * @return array Array of escaped group field => group value pairs
		 */
		protected function _conditionsForGroups(&$model, $groupValues = false)
		{
			$conditions = array();

			$groupFields = $this->settings[$model->alias]['group_fields'];
			// Return if this model has not group fields
			if ($groupFields === false)
			{
				return $conditions;
			}
			// By default, if group values are not specified, use the old group fields
			if ($groupValues === false)
			{
				$groupValues = $this->_oldGroups[$model->alias];
			}
			// Set up conditions for each group field
			foreach ($groupFields as $groupField => $escapedGroupField)
			{
				$groupValue = null;

				if (isset($groupValues[$groupField]))
				{
					$groupValue = $groupValues[$groupField];
				}

				$conditions[] = array($escapedGroupField => $groupValue,
					);
			}

			return $conditions;
		}

		/**
		 * When doing any update all calls, you want to avoid updating the record
		 * you've just modified, as the order will have been set already, so exclude
		 * it with some conditions.
		 *
		 * @param Model $model Model object that method is triggered on
		 * @return array Array Model.primary_key <> => $id
		 */
		protected function _conditionsNotCurrent(&$model)
		{
			return array($model->escapeField($model->primaryKey) . ' <>' => $model->id);
		}

		/**
		 * Executes the update, if there are any. Called in afterSave and afterDelete.
		 *
		 * @param Model $model Model object that method is triggered on
		 * @return boolean
		 */
		protected function _updateAll(&$model)
		{
			// If there's no update to do
			if (empty($this->_update[$model->alias]))
			{
				return true;
			}

			$return = true;

			foreach ($this->_update[$model->alias] as $update)
			{
				$groupValues = false;

				if (isset($update['group_values']))
				{
					$groupValues = $update['group_values'];
				}
				// Actual conditions for the update are a combination of what's derived in
				// the beforeSave or beforeDelete, and conditions to not the record we've
				// just modified/inserted and conditions to make sure only records in the
				// current record's groups
				$conditions = array_merge($this->_conditionsForGroups($model, $groupValues),
					$this->_conditionsNotCurrent($model),
					$update['conditions']
					);

				$success = $model->updateAll($update['action'], $conditions);

				$return = $return && $success;
			}

			return $return;
		}
	}
?>