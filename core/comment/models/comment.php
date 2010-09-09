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
	class Comment extends CommentAppModel {
		public $name = 'Comment';

		public $actsAs = array(
			'Libs.Expandable'
		);

		public $hasMany = array(
			'CommentAttributes' => array(
				'className' => 'Comment.CommentAttributes'
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
			
			return array_combine($classes, $classes);
		}

		public function afterSave($created) {
			parent::afterSave($created);

			$this->__clearCache();
			return true;
		}

		public function afterDelete() {
			parent::afterDelete();

			$this->__clearCache();
			return true;
		}

		private function __clearCache() {
			App::import('Folder');

			$Folder = new Folder(CACHE . 'blog');

			$files = $Folder->read();

			if (empty($files[1])) {
				return true;
			}

			foreach($files[1] as $file) {
				if ($file == 'empty') {
					continue;
				}

				if (substr($file, 0, 15) == 'comments_count_') {
					unlink(CACHE . 'blog' . DS . $file);
				}
			}

			return true;
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