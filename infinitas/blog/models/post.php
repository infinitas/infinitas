<?php
	/**
	 * Blog Post Model class file.
	 *
	 * This is the main model for Blog Posts. There are a number of
	 * methods for getting the counts of all posts, active posts, pending
	 * posts etc.  It extends {@see BlogAppModel} for some all round
	 * functionality. look at {@see BlogAppModel::afterSave} for an example
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package blog
	 * @subpackage blog.models.post
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 */
	class Post extends BlogAppModel {
		public $name = 'Post';

		/**
		 * always sort posts so newest is at the top
		 */
		public $order = array(
			'Post.created' => 'DESC',
		);

		public $actsAs = array(
			'Feed.Feedable',
			'Tags.Taggable'
		);

		public $hasMany = array(
			'ChildPost' => array(
				'className' => 'Blog.Post',
				'foreignKey' => 'parent_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => array(
					'ChildPost.id',
					'ChildPost.title',
					'ChildPost.slug',
				),
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);

		public $belongsTo = array(
			'Locker' => array(
				'className' => 'Management.User',
				'foreignKey' => 'locked_by',
				'conditions' => '',
				'fields' => array(
					'Locker.id',
					'Locker.username'
				),
				'order' => ''
			),
			'ParentPost' => array(
				'className' => 'Blog.Post',
				'foreignKey' => 'parent_id',
				'conditions' => '',
				'fields' => array(
					'ParentPost.id',
					'ParentPost.title',
					'ParentPost.slug',
				),
				'order' => ''
			),
		);

		public function getParentPosts(){
			return $this->find(
				'list',
				array(
					'conditions' => array(
						'Post.parent_id IS NULL'
					)
				)
			);
		}
		
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'title' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the title of your post', true)
					)
				),
				'body' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter your post', true)
					)
				),
				'category_id' => array(
					'comparison' => array(
						'rule' => array('comparison', '>', 0),
						'message' => __('Please select a category', true)
					)
				)
			);
		}

		/**
		 * clear un-used tags.
		 *
		 * Check if there are any tags that are only being used by this post, and
		 * if there are remove them. keeps the tables tidy
		 */
		public function beforeDelete($return = null) {
			$data = $this->read();

			$PostsTag = ClassRegistry::init('Blog.PostsTag');
			foreach($data['Tag'] as $tag) {
				$count = $PostsTag->find(
					'count',
					array(
						'conditions' => array(
							'PostsTag.tag_id' => $tag['PostsTag']['tag_id']
							)
						)
					);

				if ($count === 1) {
					if ($this->Tag->delete($tag['PostsTag']['tag_id'])) {
						$PostsTag->delete($tag['PostsTag']['id']);
					}
				}
			}

			return true;
		}

		/**
		 * Get years and months of all posts.
		 *
		 * The years are cached cos they wont change much so it saves a
		 * little bit of database calls. only gets active posts so if a month
		 * has no active posts it will not get them.
		 *
		 * @return array $dates an array or years and months
		 */
		public function getDates() {
			$dates = Cache::read('posts_dates');
			if ($dates !== false) {
				return $dates;
			}

			$years = Cache::read('posts_dates_years');
			if ($years === false) {
				$years = $this->find(
					'all',
					array(
						'fields' => array(
							'DISTINCT year( Post.created ) as year'
						),
						'conditions' => array(
							'Post.active' => 1
						),
						'contain' => false
					)
				);

				$years = Set::extract('{n}.{n}.year', $years);
				Cache::write('posts_dates_years', $years, 'blog');
			}

			if (!is_array($years)) {
				return false;
			}

			foreach($years as $year) {
				$months = $this->find(
					'all',
					array(
						'fields' => array(
							'DISTINCT month( Post.created ) as month'
						),
						'conditions' => array(
							'Post.active' => 1
						),
						'contain' => false
					)
				);

				$months = Set::extract('{n}.{n}.month', $months);

				$m = array();
				foreach($months as $month) {
					$m[] = $month[0];
				}

				$dates[$year[0]] = $m;
			}

			Cache::write('posts_dates', $dates, 'blog');

			return $dates;
		}

		/**
		 * Gets the latest posts.
		 *
		 * returns a list of the latest addes posts
		 *
		 * @param int $limit the number of posts to return
		 * @param int $active if the posts should be active or not
		 * @return array $dates an array or years and months
		 */
		public function getLatest($limit = 10, $active = 1) {
			$cacheName = cacheName('posts_latest', array($limit, $active));
			$posts = Cache::read($cacheName, 'blog');
			if($posts !== false){
				return $posts;
			}

			$posts = $this->find(
				'list',
				array(
					'conditions' => array(
						'Post.active' => $active
						),
					'limit' => $limit,
					'order' => array(
						'Post.created' => 'DESC'
						)
					)
				);

			$posts['countAll'] = $this->find(
				'count',
				array(
					'conditions' => array(
						'Post.active' => $active
						)
					)
				);

			Cache::write($cacheName, $posts, 'blog');

			return $posts;
		}

		public function getCounts($model = null) {
			$cacheName = cacheName('posts_count', $model);
			$counts = Cache::read($cacheName, 'blog');
			if($counts !== false){
				return $counts;
			}

			$counts['active'] = $this->find(
				'count',
				array(
					'conditions' => array(
						'Post.active' => 1
					),
					'contain' => false
				)
			);
			
			$counts['pending'] = $this->find(
				'count',
				array(
					'conditions' => array(
						'Post.active' => 0
					),
					'contain' => false
				)
			);

			Cache::write($cacheName, $counts, 'blog');

			return $counts;
		}

		public function getPopular($limit = 10) {
			$cacheName = cacheName('posts_popular', $limit);
			$poular = Cache::read($cacheName, 'blog');

			if($poular !== false){
				return $poular;
			}

			$poular = $this->find(
				'list',
				array(
					'conditions' => array(
						'Post.active' => 1
					),
					'order' => array(
						'Post.views' => 'DESC'
					),
					'limit' => $limit
				)
			);

			Cache::write($cacheName, $poular, 'blog');

			return $poular;
		}

		/**
		 * Get the pending posts.
		 *
		 * if the count of pending is > the limit it will add "and more..."
		 * to the end of the list
		 *
		 * @param integer $limit how many items to return
		 * @return array the list of pending posts
		 */
		public function getPending($limit = 10) {
			$cacheName = cacheName('posts_pending', $limit);
			$pending = Cache::read($cacheName, 'blog');
			if($pending !== false){
				return $pending;
			}

			$pending = $this->find(
				'list',
				array(
					'conditions' => array(
						'Post.active' => 0
					),
					'order' => array(
						'Post.modified' => 'ASC'
					),
					'limit' => $limit
				)
			);

			$count = $this->find(
				'count',
				array(
					'conditions' => array(
						'Post.active' => 0
					)
				)
			);

			if ($count > count($pending)) {
				$pending[] = __('And More...', true);
			}

			Cache::write($cacheName, $pending, 'blog');

			return $pending;
		}


		public function findPostsByTag($tag) {
			$cacheName = cacheName('posts_by_tag', $tag);
			$tags = Cache::read($cacheName, 'blog');
			if($tags !== false){
				return $tags;
			}

			$tags = $this->Tag->find(
				'all',
				array(
					'conditions' => array(
						'or' => array(
							'Tag.id' => $tag,
							'Tag.name' => $tag
						)
					),
					'fields' => array(
						'Tag.id'
					),
					'contain' => array(
						'Post' => array(
							'fields' => array(
								'Post.id'
							)
						)
					)
				)
			);

			$tags = Set::extract('/Post/id', $tags);
			Cache::write($cacheName, $pending, 'blog');

			return $tags;
		}
		
		/**
		 * Adds BETWEEN conditions for $year and $month to any array.
		 * You can pass a custom Model and a custom created field, too.
		 *
		 * @param array $paginate the pagination array to be processed
		 * @param array $options
		 * 	###	possible options:
		 * 			- year (int) year of the format YYYY (defaults null)
		 * 			- month (int) month of the year in the format 01 - 12 (defaults null)
		 * 			- model (string) custom Model Alias to pass (defaults calling Model)
		 * 			- created (string) the name of the field to use in the Between statement (defaults 'created')
		 * @todo take just reference parameter?
		 */
		public function setPaginateDateOptions($paginate, $options = array()) {
			$default = array(
				'year' => null,
				'month' => null,
				'model' => null,
				'created' => 'created'
			);
			// Extract Options
			extract(array_merge($default, $options));

			// If nothing is given, add nothing
			if ($year === null && $month === null) {
				return $paginate;
			}

			// SQL time templates for sprintf
			$yTmplBegin = "%s-01-01 00:00:00";
			$yTmplEnd = "%s-12-31 23:59:59";
			$ymTmplBegin = "%s-%02d-01 00:00:00";
			$ymTmplEnd = "%s-%02d-%02d 23:59:59";

			if ($model === null) {
				$model = $this->alias;
			}

			if ($year === null) {
				$year = date('Y');
			}

			if ($month !== null) {
				// Get days for selected month
				$days = cal_days_in_month(CAL_GREGORIAN, intval($month), intval($year));
				$begin = sprintf($ymTmplBegin, $year, $month);
				$end = sprintf($ymTmplEnd, $year, $month, $days);
			}

			else {
				$begin = sprintf($yTmplBegin, $year);
				$end = sprintf($yTmplEnd, $year);
			}

			$paginate['conditions'] += array(
				$model.$created.' BETWEEN ? AND ?' => array($begin,$end)
			);

			return $paginate;
		}

		/*
		 * Get count of tags.
		 *
		 * Used for things like generating the tag cloud.
		 */
		public function getTags($limit = 50) {
			$cacheName = cacheName('post_tags', $limit);
			$tags = Cache::read($cacheName, 'shop');
			if($tags !== false){
				return $tags;
			}

			$tags = $this->Tagged->find('cloud', array('limit' => $limit));

			Cache::write($cacheName, $tags, 'blog');
			return $tags;
		}

		public function afterSave($created){
			return $this->__dataChanged('afterSave');
		}

		public function afterDelete(){
			return $this->__dataChanged('afterDelete');
		}

		private function __dataChanged($from){
			App::import('Folder');
			$Folder = new Folder(CACHE . 'shop');
			$files = $Folder->read();

			foreach($files[1] as $file){
				if(strstr($file, 'tags_') != false){
					Cache::delete($file, 'shop');
				}
			}

			return true;
		}
	}