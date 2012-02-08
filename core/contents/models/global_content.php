<?php
	class GlobalContent extends ContentsAppModel{
		public $name = 'GlobalContent';

		public $useTable = 'global_contents';

		public $actsAs = array(
			'Contents.Taggable'
		);

		public $contentable = true;

		public $belongsTo = array(
			'GlobalLayout' => array(
				'className' => 'Contents.GlobalLayout',
				'foreignKey' => 'layout_id',
				'fields' => array(
					'GlobalLayout.id',
					'GlobalLayout.name',
					'GlobalLayout.model',
					'GlobalLayout.css',
					'GlobalLayout.html'
				)
			),
			'GlobalCategory' => array(
				'className' => 'Contents.GlobalCategory',
				'foreignKey' => 'global_category_id',
				'fields' => array(
					'GlobalCategory.id',
					'GlobalCategory.title',
				)
			),
			'Group' => array(
				'className' => 'Users.Group',
				'foreignKey' => 'group_id',
				'fields' => array(
					'Group.id',
					'Group.name'
				)
			),
			'ContentAuthor' => array(
				'className' => 'Users.User',
				'foreignKey' => 'author_id'
			),
			'ContentEditor' => array(
				'className' => 'Users.User',
				'foreignKey' => 'editor_id'
			)
		);

		public $hasMany = array(
			'Tagged' => array(
				'className' => 'Contents.GlobalTagged',
				'foreignKey' => 'foreign_key',
				array(
					'conditions' => array(
						'Tagged.model' => 'Contents.Content'
					)
				)
			),
		);

		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'title' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a name for this content item', true)
					)
				),
				'layout_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the layout for this content item', true)
					)
				),
				'body' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the body of this content item', true)
					)
				)
			);

			$this->_findMethods['duplicateData'] = true;
			$this->_findMethods['missingData'] = true;
			$this->_findMethods['shortData'] = true;
			$this->_findMethods['categoryList'] = true;
		}

		/**
		 * @brief find row with duplicate keywords and descriptions
		 *
		 * To help with SEO one needs to know if there are pages with duplicate
		 * descriptions and keywords. This method finds such rows so that the user
		 * can correct any issues.
		 *
		 * It is a custom find and can be called using pagination.
		 *
		 * @access public
		 *
		 * @code
		 *	$this->paginate = array('duplicateData');
		 *	$this->set('data', $this->paginate());
		 * @endcode
		 *
		 * @param string $state before or after (the find)
		 * @param array $query the qurey being done
		 * @param array $results the results from the find
		 *
		 * @return array in before its a query, in after its the data
		 */
		function _findDuplicateData($state, $query, $results = array()) {
			if ($state === 'before') {
				if(!is_array($query['fields'])) {
					$query['fields'] = array($query['fields']);
				}

				$query['fields'] = array_merge(
					$query['fields'],
					array(
						$this->alias . '.*',
						'GlobalContentDuplicate.id',
						'GlobalContentDuplicate.model',
						'GlobalContentDuplicate.title',
						'GlobalContentDuplicate.meta_keywords',
						'GlobalContentDuplicate.meta_description',
					)
				);
				$query['fields'] = array_unique($query['fields']);

				$query['joins'][] = array(
					'table' => 'global_contents',
					'alias' => 'GlobalContentDuplicate',
					'type' => 'LEFT',
					'conditions' => array(
						'GlobalContentDuplicate.id != GlobalContent.id',
						'and' => array(
							'or' => array(
								'GlobalContentDuplicate.meta_keywords = ' . $this->alias . '.meta_keywords',
								'GlobalContentDuplicate.meta_description = ' . $this->alias . '.meta_description',
							)
						)
					)
				);
				$query['group'][] = $this->alias . '.id';

				return $query;
			}

			if (!empty($query['operation'])) {
				return $this->_findPaginatecount($state, $query, $results);
			}

			return $results;
		}

		/**
		 * @brief find row with missing keywords, descriptions, layouts or categories
		 *
		 * This method finds rows with missing important data and can help identify
		 * content that would not be displayed properly (missing layouts) etc
		 *
		 * It is a custom find and can be called using pagination.
		 *
		 * @access public
		 *
		 * @code
		 *	$this->paginate = array('missingData');
		 *	$this->set('data', $this->paginate());
		 * @endcode
		 *
		 * @param string $state before or after (the find)
		 * @param array $query the qurey being done
		 * @param array $results the results from the find
		 *
		 * @return array in before its a query, in after its the data
		 */
		function _findMissingData($state, $query, $results = array()) {
			if ($state === 'before') {
				$query['conditions'] = array_merge(
					(array)$query['conditions'],
					array(
						'or' => array(
							array(
								'or' => array(
									$this->alias . '.meta_keywords IS NULL',
									$this->alias . '.meta_keywords' => ''
								),
							),
							array(
								'or' => array(
									$this->alias . '.meta_description IS NULL',
									$this->alias . '.meta_description' => ''
								),
							),
							array(
								'or' => array(
									$this->alias . '.global_category_id IS NULL',
									$this->alias . '.global_category_id' => ''
								),
							),
							array(
								'or' => array(
									$this->alias . '.layout_id IS NULL',
									$this->alias . '.layout_id' => ''
								)
							)
						)
					)
				);

				return $query;
			}

			if (!empty($query['operation'])) {
				return $this->_findPaginatecount($state, $query, $results);
			}

			return $results;
		}

		/**
		 * @brief find rows with short content lenght.
		 *
		 * With regards to SEO it could be better to have content with good
		 * keywords and description in the meta data. This method helps pinpoint
		 * content with short description / keyword fields that can then be improved
		 *
		 * It is a custom find and can be called using pagination.
		 *
		 * @access public
		 *
		 * @code
		 *	$this->paginate = array('shortData');
		 *	$this->set('data', $this->paginate());
		 * @endcode
		 *
		 * @param string $state before or after (the find)
		 * @param array $query the qurey being done
		 * @param array $results the results from the find
		 *
		 * @return array in before its a query, in after its the data
		 */
		function _findShortData($state, $query, $results = array()) {
			if ($state === 'before') {
				$query['conditions'] = array_merge(
					(array)$query['conditions'],
					array(
						'or' => array(
							array(
								'and' => array(
									'LENGTH(' . $this->alias . '.meta_keywords) <= 10',
									'LENGTH(' . $this->alias . '.meta_keywords) >= 1'
								),
							),
							array(
								'and' => array(
									'LENGTH(' . $this->alias . '.meta_description) <= 10',
									'LENGTH(' . $this->alias . '.meta_description) >= 1'
								)
							)
						)
					)
				);

				return $query;
			}

			if (!empty($query['operation'])) {
				return $this->_findPaginatecount($state, $query, $results);
			}

			return $results;
		}

		/**
		 * @brief get a list of categories
		 *
		 * @access public
		 *
		 * @code
		 *	$this->GlobalContent->find('categoryList');
		 * @endcode
		 *
		 * @param string $state before or after (the find)
		 * @param array $query the qurey being done
		 * @param array $results the results from the find
		 *
		 * @return array in before its a query, in after its the data
		 */
		function _findCategoryList($state, $query, $results = array()) {
			$this->findQueryType = 'list';
			
			if ($state === 'before') {
				$query['conditions'] = array_merge(
					(array)$query['conditions'],
					array(
						'GlobalContent.model' => 'Contents.GlobalCategory'
					)
				);

				$query['fields'] = array(
					'GlobalContent.foreign_key',
					'GlobalContent.title',
				);

				return $query;
			}

			if (!empty($query['operation'])) {
				return $this->_findPaginatecount($state, $query, $results);
			}
			$query['list']['groupPath'] = '';
			return $this->_findList($state, $query, $results);
		}

		/**
		 * @brief migrate data from a normal model setup to the contents plugin
		 *
		 * This will try and match data from your tables to the content plugin and
		 * move it over.
		 *
		 * @access public
		 *
		 * @param string $model the name of the plugin.model to move
		 * @param int $limit the number of rows to move
		 *
		 * @return array with how many items were found and how many were moved
		 */
		public function moveContent($model = null, $limit = 500){
			if(!$model){
				trigger_error(__('No model selected to move', true), E_USER_WARNING);
				return false;
			}

			if(!is_int($limit)){
				$limit = 500;
			}

			$return = array();
			$return['moved'] = 0;

			$Model = ClassRegistry::init($model);			
			$return['total'] = $Model->find(
				'count',
				array(					
					'conditions' => array(
						$Model->alias . '.' . $Model->displayField . ' IS NOT NULL'
					)
				)
			);

			if($Model->displayField == $Model->primaryKey){
				trigger_error(sprintf(__('Display field and primary key are the same for %s, cant move', true), $model), E_USER_WARNING);
				return false;
			}

			$rows = $Model->find(
				'all',
				array(
					'conditions' => array(
						$Model->alias . '.' . $Model->displayField . ' IS NOT NULL'
					),
					'contain' => false,
					'limit' => $limit
				)
			);
			
			foreach($rows as $row){
				$newContent = array();
				$newContent[$this->alias] = $row[$Model->alias];
				$newContent[$this->alias]['foreign_key'] = $row[$Model->alias][$Model->primaryKey];
				$newContent[$this->alias]['model'] = $Model->plugin . '.' . $Model->alias;

				if(!isset($newContent[$this->alias]['group_id'])) {
					$newContent[$this->alias]['group_id'] = 2;
				}
				
				unset($newContent[$this->alias][$Model->primaryKey]);
				$this->create();
				if($this->save($newContent)){
					$Model->id = $row[$Model->alias][$Model->primaryKey];
					$Model->saveField($Model->displayField, '', false);
					$return['moved']++;
				}
			}

			return $return;
		}

		/**
		 * @brief get counts of new content vs deleted content vs edited content
		 *
		 * @access public
		 *
		 * @param int $months the number of months back to look
		 * 
		 * @return array the data found
		 */
		public function getNewContentByMonth($months = 24) {
			$this->virtualFields['post_date'] = 'CONCAT_WS("/", YEAR(`' . $this->alias . '`.`created`), LPAD(MONTH(`' . $this->alias . '`.`created`), 2, 0))';
			$this->virtualFields['count_joins'] = 'COUNT(`' . $this->alias . '`.`id`)';

			$i = - $months;
			$dates = array();
			while($i <= 0) {
				$dates[date('Y/m', mktime(0, 0, 0, date('m') + $i, 1, date('Y')))] = null;
				$i++;
			}

			$new = $this->find(
				'list',
				array(
					'fields' => array(
						'post_date',
						'count_joins',
					),
					'conditions' => array(
						$this->alias . '.created >= ' => date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - $months, date('d'), date('Y')))
					),
					'group' => array(
						'post_date'
					)
				)
			);

			$updated = $this->find(
				'list',
				array(
					'fields' => array(
						'post_date',
						'count_joins',
					),
					'conditions' => array(
						$this->alias . '.created >= ' => date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - $months, date('d'), date('Y'))),
						$this->alias . '.created != ' . $this->alias . '.modified'
					),
					'group' => array(
						'post_date'
					)
				)
			);


			$Trash = ClassRegistry::init('Trash.Trash');
			$Trash->virtualFields['post_date'] = 'CONCAT_WS("/", YEAR(`' . $Trash->alias . '`.`deleted`), LPAD(MONTH(`' . $Trash->alias . '`.`deleted`), 2, 0))';
			$Trash->virtualFields['count_joins'] = 'COUNT(`' . $Trash->alias . '`.`id`)';

			$deleted = $Trash->find(
				'list',
				array(
					'fields' => array(
						'post_date',
						'count_joins',
					),
					'conditions' => array(
						$Trash->alias . '.model LIKE ' => 'Contents%',
						$Trash->alias . '.deleted >= ' => date('Y-m-d H:i:s', mktime(0, 0, 0, date('m') - $months, date('d'), date('Y')))
					),
					'group' => array(
						'post_date'
					)
				)
			);

			$labels = array();
			foreach(array_keys($dates) as $k => $v) {
				if($k % 2 == 0) {
					$labels[] = $v;
				}
				else {
					$labels[] = '';
				}
			}
			$dates = array_fill_keys(array_keys($dates), 0);
			
			return array(
				'labels' => $labels,
				'new' => array_merge($dates, $new),
				'updated' => array_merge($dates, $updated),
				'deleted' => array_merge($dates, $deleted)
			);
		}
	}