<?php
	/**
	 * @brief Comment Model class file handles comment CRUD.
	 *
	 * This is the model that handles comment saving and other CRUD actions, the
	 * commentable behavior will auto relate and attach this model to the models
	 * that need it. If your tables do not allow this you can do it yourself using
	 * $hasMany param in your model
	 *
	 * The model has a few methods for getting some data like new comments, pending
	 * and other thigns that may be of use in an application
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Comments.models
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	class Comment extends CommentsAppModel {
		/**
		 * the model name
		 *
		 * @var string
		 * @access public
		 */
		public $name = 'Comment';

		/**
		 * behaviors that are attached to the model.
		 *
		 * @var array
		 * @access public
		 */
		public $actsAs = array(
			'Libs.Expandable'
		);

		/**
		 * relations for the model
		 *
		 * @var array
		 * @access public
		 */
		public $hasMany = array(
			'CommentAttribute' => array(
				'className' => 'Comments.CommentAttribute'
			)
		);
		
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'email' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter your email address', true)
					),
					'email' => array(
						'rule' => array('email'),
						'message' => __('Please enter a valid email address', true)
					)
				),
				'comment' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter your comments', true)
					)
				)
			);
		}

		/**
		 * @brief hack to get the attributes for comments
		 * 
		 * @todo this is a hack to get the atributes in the comment, this should
		 * be handled in the attributes behavior but cake does not do model callbacks
		 * 3 relations deep
		 *
		 * @param array $results the data found
		 * @param bool $primary is this the primary model doing the find
		 * @access public
		 * 
		 * @return array the results after bing formatted
		 */
		public function afterFind($results, $primary){
			if(isset($results[0][0]['count'])){
				return $results;
			}

			$base = array_merge(
				array('schema' => $this->schema()),
				array('with' => 'CommentAttribute', 'foreignKey' => $this->hasMany['CommentAttribute']['foreignKey'])
			);

			if (!Set::matches('/' . $base['with'], $results)) {
				return $results;
			}

			if(isset($results[0]) || $primary){
				foreach ($results as $k => $item) {
					foreach ($item[$base['with']] as $field) {
						$results[$k][$field['key']] = $field['val'];
					}

					unset($results[$k][$base['with']]);
				}
			}

			else{
				foreach ($results[$base['with']] as $field) {
					$results[$field['key']] = $field['val'];
				}
			}

			return $results;
		}

		/**
		 * @brief get comments by user
		 *
		 * Find all comments that a particulat user has created with a limit of
		 * $limit
		 *
		 * @param string $user_id the users id
		 * @param int $limit the max number of records to get
		 * @access public
		 *
		 * @return array the comments that were found
		 */
		public function getUsersComments($user_id = null, $limit = 5){
			$comments = $this->find(
				'all',
				array(
					'conditions' => array(
						'Comment.user_id' => $user_id
					),
					'order' => array(
						'Comment.created' => 'asc'
					)
				)
			);

			return $comments;
		}

		/**
		 * @brief get some stats for notices in admin
		 *
		 * Find the number of comments that are pending and active so admin will
		 * be able to take action.
		 *
		 * @param string $class the model class that the comments should be in
		 * eg blog.post for blog comments
		 * @access public
		 *
		 * @return array
		 */
		public function getCounts($class = null) {
			if (!$class) {
				return false;
			}

			$counts = Cache::read('comments_count_' . $class);
			if ($counts !== false) {
				return $counts;
			}

			$counts['active'] = $this->find(
				'count',
				array(
					'conditions' => array(
						'Comment.active' => 1,
						'Comment.class' => $class
					),
					'contain' => false
				)
			);

			$counts['pending'] = $this->find(
				'count',
				array(
					'conditions' => array(
						'Comment.active' => 0,
						'Comment.class' => $class
					),
					'contain' => false
				)
			);

			Cache::write('comments_count_' . $class, $counts, 'blog');

			return $counts;
		}

		/**
		 * @brief get a list of all the models that have comments
		 *
		 * @todo add cache
		 * 
		 * @return array list of model classes
		 */
		public function getUniqueClassList(){
			$this->displayField = 'class';
			$classes = $this->find(
				'list',
				array(
					'group' => array(
						'Comment.class'
					),
					'order' => array(
						'Comment.class' => 'asc'
					)
				)
			);

			if(empty($classes)){
				return array();
			}
			
			return array_combine($classes, $classes);
		}

		/**
		 * @brief get a list of the latest comments
		 *
		 * used in things like comment wigets etc. will get a list of comments
		 * from the site. 
		 *
		 * @param bool $all all or just active
		 * @param int $limit the msx number of comments to get
		 * @access public
		 *
		 * @return array the comments found
		 */
		public function latestComments($all = true, $limit = 10){
			$cacheName = cacheName('latest_comments_', array($all, $limit));
			$comments = Cache::read($cacheName, 'core');
			if (!empty($comments)) {
				return $comments;
			}

			$conditions = array();
			if (!$all) {
				$conditions = array('Comment.active' => 1);
			}

			$comments = $this->find(
				'all',
				array(
					'conditions' => $conditions,
					'limit' => (int)$limit,
					'order' => array(
						'Comment.created' => 'DESC'
					)
				)
			);

			Cache::write($cacheName, $comments, 'core');

			return $comments;
		}
	}