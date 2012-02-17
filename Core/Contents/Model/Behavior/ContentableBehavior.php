<?php
	App::uses('ModelBehavior', 'Model');
	class ContentableBehavior extends ModelBehavior {
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
		}

		/**
		 * Auto contain the needed relations to the find
		 *
		 * @param object $Model the model doing the find
		 * @param array $query the conditions of the find
		 * @return array the modified query
		 */
		public function beforeFind($Model, $query) {
			$ignore = array(
				//'count',
				'getRelationsCategory'
			);
			if(in_array($Model->findQueryType, $ignore)) {
				return $query;
			}

			if(empty($query['fields'])) {
				$query['fields'] = array($Model->alias . '.*');
			}
			if(!is_array($query['fields'])) {
				$query['fields'] = array($query['fields']);
			}

			if($Model->findQueryType == 'list') {
				$displayField = 'GlobalContent.title';
				if($Model->displayField != $Model->primaryKey) {
					$displayField = $Model->alias . '.' . $Model->displayField;
				}

				if(empty($query['fields'])) {
					$query['fields'] = array(
						$Model->alias . '.' . $Model->primaryKey,
						$displayField
					);
				}

				$query['list']['keyPath'] = '{n}.' . $query['fields'][0];
				$query['list']['valuePath'] = '{n}.' . $query['fields'][1];
			}
			else if($Model->findQueryType != 'count') {
				$query['fields'] = array_merge(
					$query['fields'],
					array(
						'GlobalContent.*',
						'Layout.*',
						'GlobalCategory.*',
						'GlobalCategoryContent.id',
						'GlobalCategoryContent.title',
						'GlobalCategoryContent.slug',
						'ContentGroup.id',
						'ContentGroup.name',
						'ContentEditor.id',
						'ContentEditor.username',
						'ContentAuthor.id',
						'ContentAuthor.username'
					)
				);
			}

			if($Model->alias != 'GlobalContent') {
				$query['joins'][] = array(
					'table' => 'global_contents',
					'alias' => 'GlobalContent',
					'type' => 'LEFT',
					'conditions' => array(
						'GlobalContent.foreign_key = ' . $Model->alias . '.' . $Model->primaryKey,
					)
				);
			}

			$query['joins'][] = array(
				'table' => 'core_groups',
				'alias' => 'ContentGroup',
				'type' => 'LEFT',
				'conditions' => array(
					'ContentGroup.id = GlobalContent.group_id'
				)
			);

			if($Model->alias != 'GlobalCategory') {
				$query['joins'][] = array(
					'table' => 'global_categories',
					'alias' => 'GlobalCategory',
					'type' => 'LEFT',
					'conditions' => array(
						'GlobalCategory.id = GlobalContent.global_category_id'
					)
				);
			}

			$query['joins'][] = array(
				'table' => 'global_contents',
				'alias' => 'GlobalCategoryContent',
				'type' => 'LEFT',
				'foreignKey' => false,
				'conditions' => array(
					'GlobalCategoryContent.model' => 'Contents.GlobalCategory',
					'GlobalCategoryContent.foreign_key = GlobalCategory.id',
				)
			);

			$query['joins'][] = array(
				'table' => 'global_layouts',
				'alias' => 'Layout',
				'type' => 'LEFT',
				'foreignKey' => false,
				'conditions' => array(
					'Layout.id = GlobalContent.layout_id',
				)
			);

			$query['joins'][] = array(
				'table' => 'core_users',
				'alias' => 'ContentAuthor',
				'type' => 'LEFT',
				'foreignKey' => false,
				'conditions' => array(
					'ContentAuthor.id = GlobalContent.author_id',
				)
			);

			$query['joins'][] = array(
				'table' => 'core_users',
				'alias' => 'ContentEditor',
				'type' => 'LEFT',
				'foreignKey' => false,
				'conditions' => array(
					'ContentEditor.id = GlobalContent.editor_id',
				)
			);

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

			if($Model->findQueryType == 'list' || $Model->findQueryType == 'count' || empty($results)) {
				return $results;
			}

			$globalContentIds = Set::extract('{n}.' . $Model->GlobalContent->alias . '.' . $Model->GlobalContent->primaryKey, $results);
			$globalTags = $Model->GlobalContent->GlobalTagged->find(
				'all',
				array(
					'fields' => array(
						'GlobalTagged.*',
						'GlobalTag.*',
					),
					'conditions' => array(
						'GlobalTagged.foreign_key' => $globalContentIds
					),
					'joins' => array(
						array(
							'table' => 'global_tags',
							'alias' => 'GlobalTag',
							'type' => 'LEFT',
							'foreignKey' => false,
							'conditions' => array(
								'GlobalTag.id = GlobalTagged.tag_id',
							)
						)
					)
				)
			);

			foreach($globalTags as &$tag) {
				$tag['GlobalTagged']['GlobalTag'] = $tag['GlobalTag'];
				unset($tag['GlobalTag']);
			}

			foreach($results as $k => $result) {
				$template = sprintf(
					'/GlobalTagged[foreign_key=/%s/i]',
					$results[$k]['GlobalContent'][$Model->GlobalContent->primaryKey]
				);
				$results[$k]['GlobalTagged'] = Set::extract('{n}.GlobalTagged', Set::extract($template, $globalTags));

				if(isset($results[$k]['GlobalCategoryContent'])) {
					$results[$k]['GlobalCategory'] = array_merge(
						$results[$k]['GlobalCategoryContent'],
						$results[$k]['GlobalCategory']
					);
				}
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

		/**
		 * @brief trigger the tag save
		 *
		 * As cake does not trigger events down deep relations we need to trigger
		 * the tag save here. The model being saved is the one using GlobalContent
		 * model.
		 *
		 * If there are tags, it will be called
		 *
		 * @access public
		 *
		 * @param <type> $Model
		 * @param <type> $created
		 *
		 * @return void
		 */
		public function afterSave($Model, $created) {
			if (!empty($Model->data['GlobalContent']['tags'])) {
				$Model->GlobalContent->saveTags($Model->data['GlobalContent']['tags'], $Model->GlobalContent->id);
			}
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
							'and' => array(
								$Model->GlobalContent->alias . '.model' => $Model->plugin . '.' . $Model->alias,
								$Model->GlobalContent->alias . '.slug' => $slug
							)
						)
					)
				)
			);

			return current(Set::extract('/' . $Model->GlobalContent->alias . '/foreign_key', $id));
		}

		private function __getModel($Model){
			return Inflector::camelize($Model->plugin) . '.' . $Model->alias;
		}

		public function hasLayouts($Model) {
			return ClassRegistry::init('Contents.GlobalLayout')->hasLayouts($Model->fullModelName());
		}
	}