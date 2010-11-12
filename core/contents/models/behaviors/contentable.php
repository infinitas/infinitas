<?php

	class ContentableBehavior extends ModelBehavior{
		/**
		 * Settings initialized with the behavior
		 *
		 * @access public
		 * @var array
		 */
		public $defaults = array();

		/**
		 * Contain settings indexed by model name.
		 *
		 * @var array
		 * @access private
		 */
		private $__settings = array();

		/**
		 * Initiate behaviour for the model using settings.
		 *
		 * @param object $Model Model using the behaviour
		 * @param array $settings Settings to override for model.
		 * @access public
		 */
		public function setup($Model, $settings = array()) {
			$default = $this->defaults;

			if (!isset($this->__settings[$Model->alias])) {
				$this->__settings[$Model->alias] = $default;
			}

			$this->__settings[$Model->alias] = array_merge($this->__settings[$Model->alias], (array)$settings);

			$Model->bindModel(
				array(
					'hasOne' => array(
						'GlobalContent' => array(
							'className' => 'Contents.GlobalContent',
							'foreignKey' => 'foreign_key',
							'conditions' => array(
								'or' => array(
									'GlobalContent.model' => $Model->plugin . '.' . $Model->alias
								)
							),
							'dependent' => true
						)
					)
				),
				false
			);

			$Model->Group = $Model->GlobalContent->Group;
			$Model->Layout = $Model->GlobalContent->GlobalLayout;
		}

		/**
		 * Auto contain the needed relations to the find
		 * 
		 * @param object $Model the model doing the find
		 * @param array $query the conditions of the find
		 * @return array the modified query
		 */
		public function beforeFind($Model, $query) {
			if($Model->findQueryType == 'count'){
				return $query;
			}

			$query['contain']['GlobalContent'] = array('GlobalLayout', 'Group');
			if(isset($query['recursive']) && $query['recursive'] == -1){
				$query['recursive'] = 0;
			}

			call_user_func(array($Model, 'contain'), $query['contain']);
			return $query;
		}

		/**
		 * Format the data after the find to make it look like a normal relation
		 *
		 * @param object $Model the model that did the find
		 * @param array $results the data from the find
		 * @param bool $primary is it this model doing the query
		 * 
		 * @return array the modified data from the find
		 */
		public function afterFind($Model, $results, $primary = false) {
			parent::afterFind($Model, $results, $primary);

			if($Model->findQueryType == 'list' || $Model->findQueryType == 'count' || empty($results)){
				return $results;
			}
			
			foreach($results as $k => $result){
				if(isset($results[$k]['GlobalContent']['GlobalLayout'])){
					$results[$k]['Layout'] = $results[$k]['GlobalContent']['GlobalLayout'];
				}

				if(isset($results[$k]['GlobalContent']['Group'])){
					$results[$k]['Group'] = $results[$k]['GlobalContent']['Group'];
				}
				unset($results[$k]['GlobalContent']['GlobalLayout'], $results[$k]['GlobalContent']['Group']);

				if(isset($results[$k]['GlobalContent'])){
					$results[$k][$Model->alias] = array_merge($results[$k]['GlobalContent'], $results[$k][$Model->alias]);
				}
			}

			return $results;
		}

		/**
		 * make sure the model is set for the record to be able to link
		 * 
		 * @param object $Model the model that is doing the save
		 * @return bool true to save, false to skip
		 */
		public function beforeSave($Model) {
			if(!isset($Model->data['GlobalContent']['model']) || empty($Model->data['GlobalContent']['model'])){
				$Model->data['GlobalContent']['model'] = $this->__getModel($Model);
			}
			
			return isset($Model->data['GlobalContent']['model']) && !empty($Model->data['GlobalContent']['model']);
		}

		public function getContentId($Model, $slug){
			$id = array();
			$id = $Model->GlobalContent->find(
				'first',
				array(
					'fields' => array(
						$Model->GlobalContent->alias . '.foreign_key'
					),
					'conditions' => array(
						'or' => array(
							$Model->GlobalContent->alias . '.id' => $slug,
							$Model->GlobalContent->alias . '.slug' => $slug
						)
					)
				)
			);
			
			return current(Set::extract('/' . $Model->GlobalContent->alias . '/foreign_key', $id));
		}

		private function __getModel($Model){
			return Inflector::camelize($Model->plugin) . '.' . $Model->alias;
		}
	}