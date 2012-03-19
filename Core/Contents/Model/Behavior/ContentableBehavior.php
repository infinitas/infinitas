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
		
		private $__stopwords = 'the,are,a,able,about,across,after,all,almost,also,am,among,an,and,any,are,as,at,be,because,been,but,by,can,cannot,could,dear,did,do,does,either,else,ever,every,for,from,get,got,had,has,have,he,her,hers,him,his,how,however,i,if,in,into,is,it,its,just,least,let,like,likely,may,me,might,most,must,my,neither,no,nor,not,of,off,often,on,only,or,other,our,own,rather,said,say,says,she,should,since,so,some,than,that,the,their,them,then,there,these,they,this,tis,to,too,twas,us,wants,was,we,were,what,when,where,which,while,who,whom,why,will,with,would,yet,you,your';

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

			$Model->virtualFields['content_image_path_full'] = 'IF((GlobalContent.image = \'\' OR GlobalContent.image IS NULL), "/contents/img/no-image.png", CONCAT("/files/global_content/image/", GlobalContent.dir, "/", GlobalContent.image))';
			foreach($Model->GlobalContent->actsAs['Filemanager.Upload']['image']['thumbnailSizes'] as $name => $size) {
				$Model->virtualFields['content_image_path_' . $name] = 'IF((GlobalContent.image = "" OR GlobalContent.image IS NULL), "/contents/img/no-image.png", CONCAT("/files/global_content/image/", GlobalContent.dir, "/", "' . $name . '_", GlobalContent.image))';
			}
		}
		
		public function contentStopWords() {
			return $this->__stopwords;
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
			
			
			$imageFields = array('content_image_path_full', 'GlobalContent.dir');
			foreach($Model->virtualFields as $k => $v) {
				if(strstr($k, 'content_image_path')) {
					$imageFields[] = $k;
				}
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
			
			else if($Model->findQueryType == 'neighbors') {
				
				$query['fields'] = array_merge(
					$query['fields'],
					$imageFields,
					array('GlobalContent.id', 'GlobalContent.title', 'GlobalContent.slug')
				);
				
				$gc = array(
					'table' => 'global_contents',
					'alias' => 'GlobalContent',
					'type' => 'LEFT',
					'conditions' => array(
						'GlobalContent.foreign_key = ' . $Model->alias . '.' . $Model->primaryKey,
						'GlobalContent.model' => $Model->modelName(),
					)
				);
				
				array_unshift($query['joins'], $gc);
				
				return $query;
			}
			else if($Model->findQueryType == 'layoutList') {
				return $query;
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
						'GlobalCategoryContent.meta_keywords',
						'GlobalCategoryContent.meta_description',
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
			
			$query['fields'] = array_merge(
				$query['fields'],
				$imageFields
			);

			if($Model->alias != 'GlobalContent') {
				$gc = array(
					'table' => 'global_contents',
					'alias' => 'GlobalContent',
					'type' => 'LEFT',
					'conditions' => array(
						'GlobalContent.foreign_key = ' . $Model->alias . '.' . $Model->primaryKey,
						'GlobalContent.model' => $Model->modelName(),
					)
				);
				
				$done = false;
				foreach($query['joins'] as $join) {
					if($join == $gc) {
						$done = true;
					}
				}
				
				if(!$done) {
					array_unshift($query['joins'], $gc);
				}
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

			$ignore = array(
				'list',
				'count',
				'layoutList'
			);
			
			if(in_array($Model->findQueryType, $ignore) || empty($results)) {
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
			
			if(!isset($Model->data['GlobalContent']['introduction'])) {
				$Model->data['GlobalContent']['introduction'] = '';
			}
			
			$this->__fullTextSave($Model);

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
		
		private function __fullTextSave($Model) {
			if(empty($Model->data['GlobalContent']['body'])) {
				return false;
			}
			
			$Model->data['GlobalContent']['full_text_search'] = strip_tags(
				str_replace(array($Model->data['GlobalContent']['introduction'], "\r", "\n", "\t", '  '), ' ', $Model->data['GlobalContent']['body'])
			);
			
			$Model->data['GlobalContent']['full_text_search'] = preg_replace(
				array(
					'/[^A-Za-z\s]/i',  sprintf('/(%s)/i', str_replace(',', '\s)|(\s', $this->__stopwords)),
				), 
				' ', 
				$Model->data['GlobalContent']['full_text_search']
			);
			
			$Model->data['GlobalContent']['full_text_search'] = strtolower(
				$Model->data['GlobalContent']['title'] . ' ' . $Model->data['GlobalContent']['full_text_search']
			);
			
			$Model->data['GlobalContent']['keyword_density'] = $this->__calculateKeywordDensity(
				$Model->data['GlobalContent']['full_text_search'],
				$this->mainKeywords($Model, $Model->data['GlobalContent']['full_text_search'], 1)
			);
		}
		
		private function __calculateKeywordDensity(&$fullText, $keyword) {
			return round(((count(explode(' ', current(array_values($keyword)))) * count(explode(' ', current(array_keys($keyword))))) /
					count(explode(' ', $fullText))) * 100, 3);
		}
		
		public function mainKeywords($Model, $fullText = null, $keywordCount = 10) {
			if(empty($fullText)) {
				return array();
			}
			$phraseMap = array();
			
			$tok = strtok($fullText, ' ');
			$count = 0;
			$phrase = '';
			$lastTok = array();
			while ($tok !== false) {
				if(strlen($tok) <= 2) {
					$tok = strtok(' ');
					continue;
				}
				
				if(count($lastTok) >= 3) {
					$phrase = implode(' ', $lastTok);
					if(isset($phraseMap[$phrase])) {
						$phraseMap[$phrase]++;
					}
					else{
						$phraseMap[$phrase] = 1;
					}
				}
				
				if($count % 2) {
					$phrase = end($lastTok) . ' ' . $tok;
					if(isset($phraseMap[$phrase])) {
						$phraseMap[$phrase]++;
					}
					else{
						$phraseMap[$phrase] = 1;
					}
					
					if(count($phrase) >= 3) {
						$phrase = implode(' ', $lastTok);
						if(isset($phraseMap[$phrase])) {
							$phraseMap[$phrase]++;
						}
						else{
							$phraseMap[$phrase] = 1;
						}
					}
				}
				else {
					$phrase = end($lastTok) . ' ' . $tok;
					if(isset($phraseMap[$phrase])) {
						$phraseMap[$phrase]++;
					}
					else{
						$phraseMap[$phrase] = 1;
					}
					
					if(count($phrase) >= 3) {
						$phrase = implode(' ', $lastTok);
						if(isset($phraseMap[$phrase])) {
							$phraseMap[$phrase]++;
						}
						else{
							$phraseMap[$phrase] = 1;
						}
					}
				}
				
				if(count($lastTok) >= 3) {
					array_shift($lastTok);
					$lastTok = array_values($lastTok);
				}
				
				$lastTok[] = $tok;
				$tok = strtok(' ');
				$count++;
			}
			
			if(empty($phraseMap)) {
				return array();
			}
			
			asort($phraseMap, SORT_NUMERIC);
			return current(array_chunk(array_reverse($phraseMap), $keywordCount, true));
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