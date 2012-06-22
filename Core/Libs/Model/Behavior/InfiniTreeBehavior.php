<?php
	App::import('Behavior', 'Tree');

	class InfiniTreeBehavior extends TreeBehavior {
		/**
		 * The old parent id that will be updated with countercache
		 * when moving nodes
		 *
		 * @var mixed
		 * @access private
		 */
		private $__oldParentId = null;

		/**
		 * The parent that will be updated when removing a node for countercache
		 *
		 * @var mixed
		 * @access private
		 */
		private $__parentId = null;

		/**
		 * Initiate InfiniTree behavior
		 *
		 * @param object $Model instance of model
		 * @param array $config array of configuration settings.
		 * @return void
		 * @access public
		 */
		public function setup($Model, $config = array()) {

			$defaults = array(
				'scopeField' => false,
				'counterCache' => false,
				'directCounterCache' => false
			);

			$config = array_merge(array('scopeField' => false, 'counterCache' => false, 'directCounterCache' => false), $config);

			//Set default counterCache fieldnames if none specified
			if($config['counterCache'] === true) {
				$config['counterCache'] = 'children_count';
			}
			if($config['directCounterCache'] === true) {
				$config['directCounterCache'] = 'direct_children_count';
			}

			return parent::setup($Model, $config);
		}

		/**
		 * After save method. Called after all saves
		 *
		 * Overriden to transparently manage setting the lft and rght fields if and only if the parent field is included in the
		 * parameters to be saved.
		 *
		 * Will trigger counterCache updates if enabled, tries to set the scope based on passed data.
		 *
		 * @param AppModel $Model Model instance.
		 * @param boolean $created indicates whether the node just saved was created or updated
		 * @return boolean true on success, false on failure
		 * @access public
		 */
		public function afterSave($Model, $created) {
			if($this->scoped($Model)) {
				$this->__setScope($Model, $Model->data[$Model->alias]);
			}

			//Pass on to TreeBehavior to do stuff that trees like to do
			$return = parent::afterSave($Model, $created);

			if($this->counterCacheEnabled($Model)) {
				$parent_id = $Model->field($this->settings[$Model->alias]['parent']);
				if($parent_id) {
					$this->updateTreeCounterCache($Model, $parent_id);
				}

				if($this->counterCacheEnabled($Model) && isset($this->__oldParentId) && !empty($this->__oldParentId)) {
					$this->updateTreeCounterCache($Model, $this->__oldParentId);

					$this->__oldParentId = null;
				}
			}

			return $return;
		}

		/**
		 * Before delete method. Called before all deletes
		 *
		 * Will delete the current node and all children using the deleteAll method and sync the table.
		 *
		 * If scoped it will automatically set the needed scope data for the extended TreeBehavior.
		 *
		 * @param AppModel $Model Model instance
		 * @return boolean true to continue, false to abort the delete
		 * @access public
		 */
		public function beforeDelete($Model) {
			if($this->scoped($Model)) {
				$this->__setScope($Model);
			}

			if($this->counterCacheEnabled($Model)) {
				$this->__parentId = $Model->field($this->settings[$Model->alias]['parent']);
			}

			return parent::beforeDelete($Model);
		}

		/**
		 * If countercache is enabled, the parent id will be updated with the correct counts.
		 *
		 * @param AppModel $Model Model instance
		 * @return boolean true to continue, false to abort the delete
		 * @access public
		 */
		public function afterDelete($Model) {
			if($this->counterCacheEnabled($Model) && $this->__parentId) {
				$this->updateTreeCounterCache($Model, $this->__parentId);
			}

			return parent::afterDelete($Model);
		}

		public function beforeSave($Model) {
			if($this->scoped($Model)) {
				if(!$Model->id || $Model->id && array_key_exists($this->settings[$Model->alias]['parent'], $Model->data)) {
					if(!$this->__setScope($Model, $Model->data[$Model->alias], true)) {
						return false;
					}
				}
			}

			if($this->counterCacheEnabled($Model) && $Model->id) {
				$this->__oldParentId = $Model->field($this->settings[$Model->alias]['parent']);
			}

			return parent::beforeSave($Model);
		}

		/**
		 * Get the number of child nodes
		 *
		 * If the direct parameter is set to true, only the direct children are counted (based upon the parent_id field)
		 * If false is passed for the id parameter, all top level nodes are counted, or all nodes are counted.
		 *
		 * Scoped behavior:
		 *
		 * If the model is scoped and the id parameter is given it will automatically set the scope. To count the top
		 * level nodes you have to pass the scopeField as the id parameter.
		 *
		 * Example: array('scope' => 5) or array('id' => false, 'scope' => 5)
		 *
		 * @param AppModel $Model Model instance
		 * @param mixed $id The ID of the record to read or false to read all top level nodes
		 * @param boolean $direct whether to count direct, or all, children
		 * @return integer number of child nodes
		 * @access public
		 * @link http://book.cakephp.org/view/1347/Counting-children
		 */
		public function childcount($Model, $id = null, $direct = false) {
			if($this->scoped($Model)) {
				if(empty($id)) {
					return false;
				}

				$id = $this->__setScopeFromId($Model, $id);
			}

			return parent::childCount($Model, (string)$id, (bool)$direct);
		}

		/**
		 * Get the child nodes of the current model
		 *
		 * If the direct parameter is set to true, only the direct children are returned (based upon the parent_id field)
		 * If false is passed for the id parameter, top level, or all (depending on direct parameter appropriate) are counted.
		 *
		 * Scoped behavior:
		 *
		 * If the model is scoped and the id parameter is given it will automatically set the scope. For no id parameter
		 * you will have to pass the scope.
		 *
		 * Example: array('scope' => 5) or array('id' => false, 'scope' => 5)
		 *
		 * @param AppModel $Model Model instance
		 * @param mixed $id The ID of the record to read
		 * @param boolean $direct whether to return only the direct, or all, children
		 * @param mixed $fields Either a single string of a field name, or an array of field names
		 * @param string $order SQL ORDER BY conditions (e.g. "price DESC" or "name ASC") defaults to the tree order
		 * @param integer $limit SQL LIMIT clause, for calculating items per page.
		 * @param integer $page Page number, for accessing paged data
		 * @param integer $recursive The number of levels deep to fetch associated records
		 * @return array Array of child nodes
		 * @access public
		 * @link http://book.cakephp.org/view/1346/Children
		 */
		public function children($Model, $id = null, $direct = false, $fields = null, $order = null, $limit = null, $page = 1, $recursive = null) {
			if($this->scoped($Model)) {
				if(empty($id)) {
					return false;
				}

				$id = $this->__setScopeFromId($Model, $id);
			}

			return parent::children($Model, $id, $direct, $fields, $order, $limit, $page, $recursive);
		}

		/**
		 * A convenience method for returning a hierarchical array used for HTML select boxes
		 *
		 * For the scoped behavior you always have to pass the scope conditions with the conditions.
		 *
		 * @param AppModel $Model Model instance
		 * @param mixed $conditions SQL conditions as a string or as an array('field' =>'value',...)
		 * @param string $keyPath A string path to the key, i.e. "{n}.Post.id"
		 * @param string $valuePath A string path to the value, i.e. "{n}.Post.title"
		 * @param string $spacer The character or characters which will be repeated
		 * @param integer $recursive The number of levels deep to fetch associated records
		 * @return array An associative array of records, where the id is the key, and the display field is the value
		 * @access public
		 * @link http://book.cakephp.org/view/1348/generateTreeList
		 */
		public function generateTreeList($Model, $conditions = null, $keyPath = null, $valuePath = null, $spacer = '_', $recursive = null) {
			if($this->scoped($Model)) {
				$this->__setScope($Model, $conditions);
			}
			return parent::generateTreeList($Model, $conditions, $keyPath, $valuePath, $spacer, $recursive);
		}

		/**
		 * Get the path to the given node
		 *
		 * If the model is scoped and the id parameter is given it will automatically set the scope. For no id parameter
		 * you will have to pass the scope.
		 *
		 * Example: array('scope' => 5) or array('id' => false, 'scope' => 5)
		 *
		 * @param AppModel $Model Model instance
		 * @param mixed $id The ID of the record to read
		 * @param mixed $fields Either a single string of a field name, or an array of field names
		 * @param integer $recursive The number of levels deep to fetch associated records
		 * @return array Array of nodes from top most parent to current node
		 * @access public
		 * @link http://book.cakephp.org/view/1350/getpath
		 */
		public function getpath($Model, $id = null, $fields = null, $recursive = null) {
			if($this->scoped($Model)) {
				$id = $this->__setScopeFromId($Model, $id);

				if(empty($id)) {
					return false;
				}
			}

			return parent::getpath($Model, $id, $fields, $recursive);
		}

		/**
		 * Reorder the node without changing the parent.
		 *
		 * If the node is the last child, or is a top level node with no subsequent node this method will return false
		 *
		 * @param AppModel $Model Model instance
		 * @param mixed $id The ID of the record to move
		 * @param int|bool $number how many places to move the node or true to move to last position
		 * @return boolean true on success, false on failure
		 * @access public
		 * @link http://book.cakephp.org/view/1352/moveDown
		 */
		public function movedown($Model, $id = null, $number = 1) {
			if($this->scoped($Model)) {
				$id = $this->__setScopeFromId($Model, $id);

				if(empty($id)) {
					return false;
				}
			}

			return parent::movedown($Model, $id, $number);
		}

		/**
		 * Reorder the node without changing the parent.
		 *
		 * If the node is the first child, or is a top level node with no previous node this method will return false
		 *
		 * @param AppModel $Model Model instance
		 * @param mixed $id The ID of the record to move
		 * @param int|bool $number how many places to move the node, or true to move to first position
		 * @return boolean true on success, false on failure
		 * @access public
		 * @link http://book.cakephp.org/view/1353/moveUp
		 */
		public function moveup($Model, $id = null, $number = 1) {
			if($this->scoped($Model)) {
				if(empty($id)) {
					return false;
				}

				$id = $this->__setScopeFromId($Model, $id);
			}

			return parent::moveup($Model, $id, $number);
		}

		/**
		 * Recover a corrupted tree
		 *
		 * The mode parameter is used to specify the source of info that is valid/correct. The opposite source of data
		 * will be populated based upon that source of info. E.g. if the MPTT fields are corrupt or empty, with the $mode
		 * 'parent' the values of the parent_id field will be used to populate the left and right fields. The missingParentAction
		 * parameter only applies to "parent" mode and determines what to do if the parent field contains an id that is not present.
		 *
		 * @todo Could be written to be faster, *maybe*. Ideally using a subquery and putting all the logic burden on the DB.
		 * @param AppModel $Model Model instance
		 * @param string $mode parent or tree
		 * @param mixed $missingParentAction 'return' to do nothing and return, 'delete' to
		 * delete, or the id of the parent to set as the parent_id
		 * @param string $scope The scopeField to select which tree to recover
		 * @return boolean true on success, false on failure
		 * @access public
		 * @link http://book.cakephp.org/view/1628/Recover
		 */
		public function recover($Model, $mode = 'parent', $missingParentAction = null, $scope = null) {
			if($this->scoped($Model)) {
				if(empty($scope)) {
					return false;
				}

				$this->__setScope($Model, $scope);
			}

			return parent::recover($Model, $mode, $missingParentAction);
		}

		/**
		 * Reorder method.
		 *
		 * Reorders the nodes (and child nodes) of the tree according to the field and direction specified in the parameters.
		 * This method does not change the parent of any node.
		 *
		 * If the id parameter is not given the scope parameter is required to make this work on scoped trees.
		 *
		 * Requires a valid tree, by default it verifies the tree before beginning.
		 *
		 * Options:
		 *
		 * - 'id' id of record to use as top node for reordering
		 * - 'field' Which field to use in reordeing defaults to displayField
		 * - 'order' Direction to order either DESC or ASC (defaults to ASC)
		 * - 'verify' Whether or not to verify the tree before reorder. defaults to true.
		 * - 'scope' Manually set the scope to reorder the tree on.
		 *
		 * @param AppModel $Model Model instance
		 * @param array $options array of options to use in reordering.
		 * @return boolean true on success, false on failure
		 * @link http://book.cakephp.org/view/1355/reorder
		 * @link http://book.cakephp.org/view/1629/Reorder
		 */
		public function reorder($Model, $options = array()) {
			if($this->scoped($Model)) {
				if(empty($options[$Model->primaryKey]) && empty($options['scope'])) {
					return false;
				}

				if(!empty($options[$Model->primaryKey])) {
					$scope = $this->__getScopeFromid($Model, $options[$Model->primaryKey]);
				} else {
					$scope = $options['scope'];
				}

				$this->__setScope($Model, $scope);
			}

			return parent::reorder($Model, $options);
		}

		/**
		 * Remove the current node from the tree, and reparent all children up one level.
		 *
		 * If the parameter delete is false, the node will become a new top level node. Otherwise the node will be deleted
		 * after the children are reparented.
		 *
		 * If the model is scoped and the id parameter is given it will automatically set the scope. For no id parameter
		 * you will have to pass the scope.
		 *
		 * Example: array('scope' => 5) or array('id' => false, 'scope' => 5)
		 *
		 * @param AppModel $Model Model instance
		 * @param mixed $id The ID of the record to remove
		 * @param boolean $delete whether to delete the node after reparenting children (if any)
		 * @return boolean true on success, false on failure
		 * @access public
		 * @link http://book.cakephp.org/view/1354/removeFromTree
		 */
		public function removefromtree($Model, $id = null, $delete = false, $scopeField = null) {
			if($this->scoped($Model)) {
				$id = $this->__setScopeFromId($Model, $id);

				if(empty($id)) {
					return false;
				}
			}

			return parent::removefromtree($Model, $id, $delete);
		}

		/**
		 * Check if the current tree is valid.
		 *
		 * Returns true if the tree is valid otherwise an array of (type, incorrect left/right index, message)
		 *
		 * @param AppModel $Model Model instance
		 * @param string $scope The scoped used to select the tree to verify
		 * @return mixed true if the tree is valid or empty, otherwise an array of (error type [index, node],
		 *  [incorrect left/right index,node id], message)
		 * @access public
		 * @link http://book.cakephp.org/view/1630/Verify
		 */

		public function verify($Model, $scope = null) {
			if($this->scoped($Model)) {
				$this->__setScope($Model, $scope);
			}

			return parent::verify($Model);
		}

		/**
		 * Mass create nodes to initialize trees. If the model is scoped you need to pass 'scope' to the options array.
		 *
		 * Example data parameter structure:
		 *
		 *	Array
		 *	(
		 *		[ScopedNumberTree] => Array
		 *			(
		 *				[0] => Array
		 *					(
		 *						[name] => United Kingdom
		 *						[ScopedNumberTree] => Array
		 *							(
		 *								[0] => Array
		 *									(
		 *										[name] => Sales
		 *									)
		 *
		 *								[1] => Array
		 *									(
		 *										[name] => Marketing
		 *									)
		 *
		 *								[2] => Array
		 *									(
		 *										[name] => R&D
		 *									)
		 *
		 *							)
		 *
		 *					)
		 *
		 *				[1] => Array
		 *					(
		 *						[name] => Belgium
		 *						[ScopedNumberTree] => Array
		 *							(
		 *								[0] => Array
		 *									(
		 *										[name] => Sales
		 *									)
		 *
		 *								[1] => Array
		 *									(
		 *										[name] => Marketing
		 *									)
		 *
		 *								[2] => Array
		 *									(
		 *										[name] => R&D
		 *									)
		 *
		 *							)
		 *
		 *					)
		 *
		 *			)
		 *
		 *	)
		 *
		 *
		 * Options:
		 *
		 * - 'scope' The tree scope
		 *
		 * @param AppModel $Model Model instance
		 * @param array $data The nodes to create
		 * @param array $options Settings
		 * @return mixed true if succesfully saved or false on error
		 * @access public
		 */
		public function treeSave($Model, $data = array(), $options = array()) {
			if(empty($data)) {
				return false;
			}

			if($this->scoped($Model)) {
				if(empty($options['scope'])) {
					return false;
				}

				$this->__setScope($Model, $options['scope']);
			}

			if(!$data || !is_array($data)) {
				return false;
			}

			if($this->__doTreeSave($Model, $data, array('scope' => $options['scope'], 'parent' => null, 'depth' => 0))) {
				return true;
			}

			return false;
		}

		public function scoped($Model) {
			return !empty($this->settings[$Model->alias]['scopeField']);
		}

		/**
		 * Iterates the tree path to recalculate all the counterCaches starting from the first node
		 *
		 * @access public
		 *
		 * @param object $Model the model that is doing the save
		 * @param bool $id The lowest node to start updating from
		 *
		 * @return Array model data
		 */
		public function updateTreeCounterCache($Model, $id=null) {
			if(is_null($id)) {
				$id = $Model->id;
			}
			if(!$id) {
				return false;
			}

			$_id = $Model->id;
			$_data = $Model->data;
			$_whitelist = $Model->whitelist;

			$Model->data = array();

			$node = $this->__getNodeInfo($Model, $id);

			$counts = array();

			//Calculate children count
			if($this->settings[$Model->alias]['counterCache']) {
				//Take a shortcut if we dont do any extra conditions
				if(empty($this->settings[$Model->alias]['conditions'])) {
					$childrenCount = ($node[$Model->alias][$this->settings[$Model->alias]['right']] - $node[$Model->alias][$this->settings[$Model->alias]['left']] - 1) / 2;
				} else {
					$childrenCount = $Model->find('count', array(
						'conditions' => array(
							array_merge(array(), array(
								$Model->alias . '.' . $this->settings[$Model->alias]['left'] . ' >' => $node[$Model->alias][$this->settings[$Model->alias]['left']],
								$Model->alias . '.' . $this->settings[$Model->alias]['right'] . ' <' => $node[$Model->alias][$this->settings[$Model->alias]['right']],
								$this->settings[$Model->alias]['scope']
							))
						),
						'contain' => false
					));
				}

				$counts[$this->settings[$Model->alias]['counterCache']] = $childrenCount;
			}

			//Calculate direct children count
			if($this->settings[$Model->alias]['directCounterCache']) {
				$directChildrenCount = $Model->find('count', array(
					'conditions' => array(
						array_merge(array(), array(
							$Model->alias . '.' . $this->settings[$Model->alias]['parent'] => $id,
							$this->settings[$Model->alias]['scope']
						))
					),
					'contain' => false
				));

				$counts[$this->settings[$Model->alias]['directCounterCache']] = $directChildrenCount;
			}
			$Model->id = $id;
			$result = $Model->save(array($Model->alias => $counts), array('fieldList' => array_keys($counts), 'validate' => false, 'callbacks' => false));

			if(!empty($node[$Model->alias][$this->settings[$Model->alias]['parent']])) {
				$this->updateTreeCounterCache($Model, $node[$Model->alias][$this->settings[$Model->alias]['parent']]);
			}

			//Restore the original id and data so other behaviors dont flip out and roll over and die
			$Model->id = $_id;
			$Model->data = $_data;
			$Model->whitelist = $_whitelist;

			return true;
		}

		/**
		 * Return the needed data to calculate the number of children for a specific node
		 *
		 * @access private
		 *
		 * @param object $Model the model that is doing the save
		 * @param bool $id The row to fetch
		 *
		 * @return Array model data
		 */
		private function __getNodeInfo($Model, $id) {
			$node = $Model->find('first', array(
				'conditions' => array(
					$Model->alias . '.' . $Model->primaryKey => $id
				),
				'fields' => array(
					$Model->alias . '.' . $this->settings[$Model->alias]['parent'],
					$Model->alias . '.' . $this->settings[$Model->alias]['left'],
					$Model->alias . '.' . $this->settings[$Model->alias]['right']
				),
				'contain' => false
			));

			return $node;
		}

		/**
		 * Return a boolean if any of the counterCache methods are enabled
		 *
		 * @access public
		 *
		 * @param object $Model the model that is doing the save
		 *
		 * @return Array model data
		 */
		public function counterCacheEnabled($Model) {
			return $this->settings[$Model->alias]['counterCache'] || $this->settings[$Model->alias]['directCounterCache'];
		}

		/**
		 *
		 * Private function that saves the nodes recursively
		 *
		 * @param AppModel $Model Model instance
		 * @param array $data The nodes to create
		 * @param array $options Settings
		 * @return mixed true if succesfully saved or false on error
		 * @access private
		 */
		private function __doTreeSave($Model, $data, $options = array()) {
			$return = false;

			//Special case in the first run
			if($options['depth'] > 0) {
				$Model->create();
				if(!$Model->save(array_diff_key(array_merge(array($this->settings[$Model->alias]['scopeField']  => $options['scope'], $this->settings[$Model->alias]['parent'] => $options['parent']), $data), array($Model->alias => $Model->alias)))) {
					return false;
				}

				$options['parent'] = $Model->getInsertID();

				$return = true;
			}

			if(array_key_exists($Model->alias, $data)) {
				$options['depth']++;

				foreach($data[$Model->alias] as $childData) {
					if(!$this->__doTreeSave($Model, $childData, $options)) {
						return false;
					};
				}

				$return = true;
			}

			return $return;
		}

		/**
		 * Private function to handle id parameters that could be the id or an array with the scope.
		 *
		 * @param AppModel $Model Model instance
		 * @param mixed $id The node id or an array containing id and/or scope.
		 * @return string The id value that the original tree behavior expects as the id parameter
		 * @access private
		 */
		private function __setScopeFromId($Model, $id) {
			if($this->scoped($Model)) {
				$scope = false;

				if(is_array($id)) {
					if(array_key_exists($Model->primaryKey, $id)) {
						$_id = $id[$Model->primaryKey];
					} else {
						$_id = null;
					}

					if(array_key_exists('scope', $id)) {
						$scope = $id['scope'];
					}

					$id = $_id;
				}

				//Scope still not given, load it from the id
				if(!$scope) {
					$scope = $this->__getScopeFromId($Model, $id);
				}

				if(!$scope) {
					return $id;
				}

				$this->__setScope($Model, $scope);
			}

			return $id;
		}

		/**
		 * Private function that tries to set the scope based on a given scope value, id, conditions or record data
		 *
		 * @param AppModel $Model Model instance
		 * @param mixed $data The data given to try and get the scope from
		 * @param bool $beforeSave Set to true when called from the beforeSave to set the scopeField to the data array
		 * @return bool True on success false if the scope couldnt be found.
		 * @access private
		 */
		private function __setScope($Model, $data = null, $beforeSave = false) {
			$scope = null;

			//Is the scope given as an id?
			if(!empty($data) && !is_array($data)) {
				$scope = $data;

			//Is the scopeField given in the data array?
			} elseif(!empty($data) && array_key_exists($this->settings[$Model->alias]['scopeField'], $data)) {
				$scope = $data[$this->settings[$Model->alias]['scopeField']];

			//Is the scopeField given in the conditions for a find?
			} elseif(!empty($data) && array_key_exists($Model->alias . '.' . $this->settings[$Model->alias]['scopeField'], $data)) {
				$scope = $data[$Model->alias . '.' . $this->settings[$Model->alias]['scopeField']];

			//Is the parent_id given in the data array?
			} elseif(!empty($data) && array_key_exists($this->settings[$Model->alias]['parent'], $data) && !empty($data[$this->settings[$Model->alias]['parent']])) {
				$scope = $this->__getScopeFromId($Model, $data[$this->settings[$Model->alias]['parent']]);
			} elseif($Model->id) {
				$scope = $this->__getScopeFromId($Model, $Model->id);
			}

			if($scope) {
				$this->settings[$Model->alias]['scope'] = array($Model->alias . '.' . $this->settings[$Model->alias]['scopeField'] => $scope);
				if($beforeSave) {
					$Model->data[$Model->alias][$this->settings[$Model->alias]['scopeField']] = $scope;
				}
				return true;
			}

			return false;
		}

		/**
		 * Returns the scope value for a given id
		 *
		 * @param AppModel $Model Model instance
		 * @param mixed $id The row to get the scope from
		 * @return mixed The scope value.
		 * @access private
		 */
		private function __getScopeFromId($Model, $id) {
			$data = $Model->find('first', array(
				'fields' => $this->settings[$Model->alias]['scopeField'],
				'conditions' => array(
					$Model->alias . '.' . $Model->primaryKey => $id
				),
				'contain' => false
			));

			return $data[$Model->alias][$this->settings[$Model->alias]['scopeField']];
		}
	}
