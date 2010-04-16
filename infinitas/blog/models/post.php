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
	var $name = 'Post';

	/**
	* always sort posts so newest is at the top
	*/
	var $order = array(
		'Post.created' => 'DESC',
	);

	var $actsAs = array(
		'Feed.Feedable',
		'Libs.Commentable',
		'Libs.Categorised'
	);

	var $hasAndBelongsToMany = array(
		'Tag' => array(
			'className'              => 'Blog.Tag',
			'joinTable'              => 'posts_tags',
			'with'                   => 'Blog.PostsTag',
			'foreignKey'             => 'post_id',
			'associationForeignKey'  => 'tag_id',
			'unique'                 => true,
			'conditions'             => '',
			'fields'                 => '',
			'order'                  => '',
			'limit'                  => '',
			'offset'                 => '',
			'finderQuery'            => '',
			'deleteQuery'            => '',
			'insertQuery'            => ''
		)
	);


	var $hasMany = array(
		'ChildPost' => array(
			'className' => 'Blog.Post',
			'foreignKey' => 'parent_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => array(
				'ChildPost.id',
				'ChildPost.title',
				'ChildPost.slug',
				'ChildPost.category_id',
			),
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

	var $belongsTo = array(
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

	function __construct($id = false, $table = null, $ds = null) {
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
	* before deleting a post.
	*
	* remove the tags
	*/
	function beforeDelete($return = null) {
		$data = $this->read();

		foreach($data['Tag'] as $tag) {
			$count = ClassRegistry::init('Blog.PostsTag')->find(
				'count',
				array(
					'conditions' => array(
						'PostsTag.tag_id' => $tag['PostsTag']['tag_id']
						)
					)
				);

			if ($count === 1) {
				if ($this->Tag->delete($tag['PostsTag']['tag_id'])) {
					ClassRegistry::init('Blog.PostsTag')->delete($tag['PostsTag']['id']);
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
	function getDates() {
		$dates = Cache::read('post_dates');
		if ($dates !== false) {
			return $dates;
		}

		$years = Cache::read('post_dates_years');
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
			Cache::write('post_dates_years', $years, 'blog');
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

		Cache::write('post_dates', $dates, 'blog');

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
	function getLatest($limit = 10, $active = 1) {
		$posts = Cache::read('post_latest-' . $limit . '-' . $active);
		if ($posts !== false) {
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

		Cache::write('post_latest-' . $limit . '-' . $active, $posts, 'blog');

		return $posts;
	}

	function getCounts($model = null) {
		$counts = Cache::read('posts_count');
		if ($counts !== false) {
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

		Cache::write('posts_count', $counts, 'blog');

		return $counts;
	}

	function getPopular($limit = 10) {
		$poular = Cache::read('posts_popular');
		if ($poular !== false) {
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

		Cache::write('posts_popular', $poular, 'blog');

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
	function getPending($limit = 10) {
		$pending = Cache::read('posts_pending');
		if ($pending !== false) {
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

		Cache::write('posts_pending', $pending, 'blog');

		return $pending;
	}
}

?>