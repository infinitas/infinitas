<?php

App::import('Behavior', 'Tree');

class InfiniTreeBehavior extends TreeBehavior {
	public $name = 'ScopedTree';

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
				debug($this->__oldParentId);
				$this->updateTreeCounterCache($Model, $this->__oldParentId);
				
				$this->__oldParentId = null;
			}
		}
	}
	
	public function beforeDelete($Model) {
		if($this->scoped($Model)) {
			$this->__setScope($Model);
		}

		if($this->counterCacheEnabled($Model)) {
			$this->__parentId = $Model->field($this->settings[$Model->alias]['parent']);
		}
		
		return parent::beforeDelete($Model);
	}
	
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

	public function childcount($Model, $id = null, $direct = false) {
		if($this->scoped($Model)) {
			if(empty($id)) {
				return false;
			}
			
			$id = $this->__setScopeFromId($Model, $id);
		}
		
		return parent::childcount($Model, $id, $direct);
	}
	
	public function children($Model, $id = null, $direct = false, $fields = null, $order = null, $limit = null, $page = 1, $recursive = null) {
		if($this->scoped($Model)) {
			if(empty($id)) {
				return false;
			}
			
			$id = $this->__setScopeFromId($Model, $id);
		}

		return parent::children($Model, $id, $direct, $fields, $order, $limit, $page, $recursive);
	}
	
	public function generatetreelist($Model, $conditions = null, $keyPath = null, $valuePath = null, $spacer = '_', $recursive = null) {
		if($this->scoped($Model)) {
			$this->__setScope($Model, $conditions);		
		}
		return parent::generatetreelist($Model, $conditions, $keyPath, $valuePath, $spacer, $recursive);
	}
	
	public function getpath($Model, $id = null, $fields = null, $recursive = null) {
		if($this->scoped($Model)) {
			$id = $this->__setScopeFromId($Model, $id);
			
			if(empty($id)) {
				return false;
			}
		}
		
		return parent::getpath($Model, $id, $fields, $recursive);
	}
	
	public function movedown($Model, $id = null, $number = 1) {
		if($this->scoped($Model)) {
			$id = $this->__setScopeFromId($Model, $id);
			
			if(empty($id)) {
				return false;
			}
		}
		
		return parent::movedown($Model, $id, $number);
	}
	
	public function moveup($Model, $id = null, $number = 1) {
		if($this->scoped($Model)) {
			if(empty($id)) {
				return false;
			}
			
			$id = $this->__setScopeFromId($Model, $id);
		}
		
		return parent::moveup($Model, $id, $number);
	}

	public function recover($Model, $mode = 'parent', $missingParentAction = null, $scope = null) {
		if($this->scoped($Model)) {
			if(empty($scope)) {
				return false;
			}
			
			$this->__setScope($Model, $scope);
		}
		
		return parent::recover($Model, $mode, $missingParentAction);
	}
	
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

	public function removefromtree($Model, $id = null, $delete = false, $scopeField = null) {
		if($this->scoped($Model)) {
			$id = $this->__setScopeFromId($Model, $id);
			
			if(empty($id)) {
				return false;
			}
		}
		
		return parent::removefromtree($Model, $id, $delete);
	}

	public function verify($Model, $scope = null) {
		if($this->scoped($Model)) {
			$this->__setScope($Model, $scope);
		}
		
		return parent::verify($Model);
	}
	
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

		if(!$data || !is_array($data)){
			return false;
		}

		if($this->__doTreeSave($Model, $data, array('scope' => $options['scope'], 'parent' => null, 'depth' => 0))) {
			return true;
		}

		return false;
	}
	
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
			$this->settings[$Model->alias]['scope'] = $Model->alias . '.' . $this->settings[$Model->alias]['scopeField'] . " = '" . $scope . "'";
			
			if($beforeSave) {
				$Model->data[$Model->alias][$this->settings[$Model->alias]['scopeField']] = $scope;
			}
			return true;
		}
		
		return false;
	}
	
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
	
	public function scoped($Model) {
		return !empty($this->settings[$Model->alias]['scopeField']);
	}
	
	
	public function updateTreeCounterCache($Model, $id=null) {$this->looper++;
		if(is_null($id)) {
			$id = $Model->id;
		}
		if(!$id) {
			return false;
		}

		$_id = $Model->id;
		$_data = $Model->data;
		
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
		$result = $Model->save(array($Model->alias => $counts), array('validate' => false, 'callbacks' => false));

		if(!empty($node[$Model->alias][$this->settings[$Model->alias]['parent']])) {
			$this->updateTreeCounterCache($Model, $node[$Model->alias][$this->settings[$Model->alias]['parent']]);
		}

		//Restore the original id and data so other behaviors dont flip out and roll over and die
		$Model->id = $_id;
		$Model->data = $_data;

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

	public function counterCacheEnabled($Model) {
		return $this->settings[$Model->alias]['counterCache'] || $this->settings[$Model->alias]['directCounterCache'];
	}
}
