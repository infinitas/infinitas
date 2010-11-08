<?php
/**
	* Comment Model class file.
	*
	* This is the main model for Blog Comments. There are a number of
	* methods for getting the counts of all comments, active comments, pending
	* comments etc.  It extends {@see BlogAppModel} for some all round
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
	* @subpackage blog.models.comment
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	*/
	class Comment extends CommentsAppModel {
		public $name = 'Comment';

		public $actsAs = array(
			'Libs.Expandable'
		);

		public $hasMany = array(
			'CommentAttribute' => array(
				'className' => 'Comments.CommentAttribute'
			)
		);

		public function afterFind($results){
			if(isset($results[0][0]['count'])){
				return $results;
			}

			$base = array('schema' => $this->schema());
			extract(
				array_merge(
					$base,
					array('with' => 'CommentAttribute', 'foreignKey' => $this->hasMany['CommentAttribute']['foreignKey'])
				)
			);
			
			if (!Set::matches('/'.$with, $results)) {
				return $results;
			}

			foreach ($results as $i => $item) {
				foreach ($item[$with] as $field) {
					$results[$i][$field['key']] = $field['val'];
				}
				unset($results[$i][$with]);
			}

			return $results;
		}
		
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
					'limit' => $limit,
					'order' => array(
						'Comment.created' => 'DESC'
					)
				)
			);

			Cache::write($cacheName, $comments, 'core');

			return $comments;
		}
	}