<?php
	class GlobalContent extends ContentsAppModel {
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
		
		public $findMethods = array(
			'contentIssues' => true,
			'categoryList' => true,
			'getRelationsCategory' => true,
		);

		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'title' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a name for this content item')
					)
				),
				'layout_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the layout for this content item')
					)
				),
				'body' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the body of this content item')
					)
				)
			);
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
		protected function _findContentIssues($state, $query, $results = array()) {
			if ($state === 'before') {
				$this->virtualFields['keyword_not_in_description']	= '!LOWER(' . $this->alias . '.meta_description) LIKE CONCAT("%", LOWER(SUBSTRING_INDEX(`' . $this->alias . '`.`meta_keywords`, ",", 1)), "%")';
				$this->virtualFields['keywords_missing']		= '(' . $this->alias . '.meta_keywords IS NULL OR ' . $this->alias . '.meta_keywords)';
				$this->virtualFields['keywords_short']			= '(LENGTH(' . $this->alias . '.meta_keywords) <= 10 AND LENGTH(' . $this->alias . '.meta_keywords) >= 1)';
				$this->virtualFields['keywords_duplicate']		= '(GlobalContentDuplicate.id != ' . $this->alias . '.id AND GlobalContentDuplicate.meta_keywords = ' . $this->alias . '.meta_keywords)';
				$this->virtualFields['keyword_density_problem']	= '(' . $this->alias . '.keyword_density < 2 OR ' . $this->alias . '.keyword_density > 5)';
				
				$this->virtualFields['description_missing']		= '(' . $this->alias . '.meta_description IS NULL OR ' . $this->alias . '.meta_description)';
				$this->virtualFields['description_short']		= '(LENGTH(' . $this->alias . '.meta_description) <= 10 AND LENGTH(' . $this->alias . '.meta_description) >= 1)';
				$this->virtualFields['description_duplicate']	= '(GlobalContentDuplicate.id != ' . $this->alias . '.id AND GlobalContentDuplicate.meta_description = ' . $this->alias . '.meta_description)';
				$this->virtualFields['description_too_long']	= 'LENGTH(' . $this->alias . '.meta_description) >= 153';
				
				$this->virtualFields['missing_category']		= '(' . $this->alias . '.model <> "Contents.GlobalCategory" AND (' . $this->alias . '.global_category_id IS NULL OR ' . $this->alias . '.global_category_id = ""))';
				
				$this->virtualFields['missing_layout']			= '(' . $this->alias . '.layout_id IS NULL OR ' . $this->alias . '.layout_id = "" )';
				$this->virtualFields['missmatched_layout']		= '(Layout.model <> '. $this->alias . '.model)';
				
				
				$this->virtualFields['introduction_duplicate']	= '(GlobalContentDuplicate.id != ' . $this->alias . '.id AND GlobalContentDuplicate.introduction = ' . $this->alias . '.introduction)';
				$this->virtualFields['body_duplicate']	= '(GlobalContentDuplicate.id != ' . $this->alias . '.id AND GlobalContentDuplicate.body = ' . $this->alias . '.body)';
				$this->virtualFields['body_word_count'] = 'SUM(LENGTH(' . $this->alias . '.body) - LENGTH(REPLACE(' . $this->alias . '.body, " ", "")) + 1)';
				
				$query['fields'] = array_merge(
					(array)$query['fields'],
					array(
						'keyword_not_in_description', 'keywords_missing', 'keywords_short', 'keywords_duplicate', 'keyword_density_problem',
						'description_missing', 'description_short', 'description_duplicate', 'description_too_long',
						'missing_category',
						'missing_layout', 'missmatched_layout',
						'introduction_duplicate', 'body_duplicate', 'body_word_count'
					)
				);
				
				$query['group'] = array(
					$this->alias . '.' . $this->primaryKey .' HAVING (' . $this->alias . '__description_too_long = 1 OR ' . 
						$this->alias . '__keyword_not_in_description = 1 OR ' .
						$this->alias . '__keywords_missing = 1 OR ' .
						$this->alias . '__keywords_short = 1 OR ' .
						$this->alias . '__keywords_duplicate = 1 OR ' .
						$this->alias . '__keyword_density_problem = 1 OR ' .
					
						$this->alias . '__description_missing = 1 OR ' .
						$this->alias . '__description_short = 1 OR ' .
						$this->alias . '__description_duplicate = 1 OR '  .
						$this->alias . '__description_too_long = 1 OR '   .
					
						$this->alias . '__missing_category = 1 OR '   .
					
						$this->alias . '__missing_layout = 1 OR '   .
						$this->alias . '__missmatched_layout = 1 OR '   .
						$this->alias . '__introduction_duplicate = 1 OR '   .
						$this->alias . '__body_duplicate = 1 OR '   .
						$this->alias . '__body_word_count < 300)' 
				);
								
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
				
				$query['order'] = array(
					'GlobalCategoryContent.title',
					'GlobalContent.model',
					'GlobalContent.title'
				);

				return $query;
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
		protected function _findCategoryList($state, $query, $results = array()) {
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

			$query['list']['groupPath'] = '';
			return $this->_findList($state, $query, $results);
		}
		
		protected function _findGetRelationsCategory($state, $query, $results = array()) {
			if ($state === 'before') {
				$query['fields'] = array(
					'GlobalContent.id',
					'GlobalContent.model',
					'GlobalContent.foreign_key',
					'GlobalContent.title',
					'GlobalContent.slug',
					'GlobalContent.introduction',
					'GlobalContent.canonical_url',
					'GlobalContent.global_category_id',
					'SubCategory.*',
					'SubCategoryData.id',
					'SubCategoryData.model',
					'SubCategoryData.foreign_key',
					'SubCategoryData.title',
					'SubCategoryData.slug',
					'SubCategoryData.introduction',
					'SubCategoryData.canonical_url',
					'SubCategoryData.global_category_id'
				);
				$query['conditions'] = array(
					'or' => array(
						'GlobalContent.foreign_key' => $query[0],
						'GlobalContent.global_category_id' => $query[0]
					)
				);
				$query['joins'][] = array(
					'table' => 'global_categories',
					'alias' => 'SubCategory',
					'type' => 'LEFT',
					'foreignKey' => false,
					'conditions' => array(
						'SubCategory.parent_id = GlobalContent.global_category_id'
					)
				);
				$query['joins'][] = array(
					'table' => 'global_contents',
					'alias' => 'SubCategoryData',
					'type' => 'LEFT',
					'foreignKey' => false,
					'conditions' => array(
						'SubCategoryData.foreign_key = SubCategory.id'
					)
				);
				$query['group'] = '`GlobalContent`.`id`';

				unset($query[0]);
				return $query;
			}
			
			$return = array();
			foreach($results as &$result) {
				$result['GlobalContent']['id'] = $result['GlobalContent']['foreign_key'];
				$model = $result['GlobalContent']['model'];
				unset($result['GlobalContent']['foreign_key'], $result['GlobalContent']['model']);

				$return[$model][] = $result['GlobalContent'];
				
				if(!empty($result['SubCategoryData']['id'])) {
					$return['Contents.SubCategory'][] = $result['SubCategoryData'];
				}
			}

			return $return;
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
				trigger_error(__('No model selected to move'), E_USER_WARNING);
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
				trigger_error(sprintf(__('Display field and primary key are the same for %s, cant move'), $model), E_USER_WARNING);
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