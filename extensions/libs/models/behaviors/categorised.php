<?php

class CategorisedBehavior extends ModelBehavior {
	/**
	* Contain settings indexed by model name.
	*
	* @var array
	* @access private
	*/
	var $__settings = array();

	var $__default = array('multiple' => false);

	/**
	* Initiate behaviour for the model using settings.
	*
	* @param object $Model Model using the behaviour
	* @param array $settings Settings to override for model.
	* @access public
	*/
	function setup(&$Model, $settings = array()) {
		$this->__settings[$Model->alias] = array_merge($this->__default, $settings);

		$relationType = $this->__settings[$Model->alias]['multiple'] == true ? 'hasMany' : 'hasOne';

		$Model->bindModel(array(
			$relationType => array(
				'CategoryItem' => array(
					'className' => 'Management.CategoryItem',
					'foreignKey' => 'foreign_id',
					'conditions' => array('CategoryItem.class' => $Model->modelName()),
					'dependent' => true
				)
			),
			'hasAndBelongsToMany' => array(
				'Category' => array(
					'className' => 'Management.Category',
					'with' => 'Management.CategoryItem',
					'foreignKey' => 'foreign_id',
					'unique' => false,
					'conditions' => array('CategoryItem.class' => $Model->modelName()),
				)
			)
		), false);
	}

	function beforeSave(&$Model) {		
		if(isset($Model->data['Category']['id']) && $this->__settings[$Model->alias]['multiple'] == false) {
			$Model->data['CategoryItem']['category_id'] = $Model->data['Category']['id'];
			$Model->data['CategoryItem']['class'] = $Model->modelName();

			unset($Model->data['Category']);
		}

		return true;
	}

	function afterSave(&$Model, $created) {
		if(isset($Model->data['CategoryItem']['category_id']) && isset($Model->data['CategoryItem']['class'])) {
			if($this->__settings[$Model->alias]['multiple'] == false) {
				$categoryItem = $Model->CategoryItem->find('first', array(
					'fields' => array('CategoryItem.id'),
					'conditions' => array(
						'CategoryItem.class' => $Model->modelName(),
						'CategoryItem.foreign_id' => $Model->id
					)
				));

				if(!empty($categoryItem['CategoryItem']['id'])) {
					$Model->data['CategoryItem']['id'] = $categoryItem['CategoryItem']['id'];
				}
				$Model->data['CategoryItem']['foreign_id'] = $Model->id;
				
				$Model->CategoryItem->save($Model->data['CategoryItem'], array('callbacks' => false));
			}
		}
	}

	function afterFind(&$Model, $results, $primary = false ) {
		if(isset($results[0]) && $this->__settings[$Model->alias]['multiple'] == false) {
			foreach($results as $key => $result) {
				if(isset($result['Category'][0])) {
					$results[$key]['Category'] = $result['Category'][0];
				}
			}
		}

		return $results;
	}

	function beforeFind(&$Model, $queryData) {
		if($Model->recursive == -1 && empty($queryData['fields'])) {
			$queryData['joins'] = array(
				array(
					'table' => 'global_category_items',
					'alias' => 'CategoryItems',
					'type' => 'LEFT',
					'conditions' => array(
						'CategoryItems.foreign_id = ' . $Model->alias . '.' . $Model->primaryKey,
						'CategoryItems.class' => $Model->modelName()
					)
				),
				array(
					'table' => 'global_categories',
					'alias' => 'Category',
					'type' => 'LEFT',
					'conditions' => array(
						'Category.id = CategoryItems.category_id'
					)
				)
			);

			
			$queryData['fields'] = array('Category.id', 'Category.title', 'Category.slug', 'CategoryItems.category_id', $Model->alias . '.*');

			if(isset($queryData['conditions']['id'])) {
				$queryData['conditions'][$Model->alias . '.id'] = $queryData['conditions']['id'];
				unset($queryData['conditions']['id']);
			}
		}

		if(isset($queryData['conditions'][$Model->alias . '.category_id'])) {
			$queryData['joins'] = array(
				array(
					'table' => 'global_category_items',
					'alias' => 'CategoryItems',
					'type' => 'LEFT',
					'conditions' => array(
						'CategoryItems.foreign_id = ' . $Model->alias . '.' . $Model->primaryKey,
						'CategoryItems.class' => $Model->modelName()
					)
				)
			);

			$queryData['conditions']['CategoryItems.category_id'] = $queryData['conditions'][$Model->alias . '.category_id'];
			unset($queryData['conditions'][$Model->alias . '.category_id']);
		}

		return $queryData;
	}

	function generateCategoryList() {
		return ClassRegistry::init('Category')->generatetreelist();
	}
}

?>