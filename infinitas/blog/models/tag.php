<?php
/**
* Blog Tag Model class file.
*
* This is the main model for Blog Tags.
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
* @subpackage blog.models.tag
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
*/
class Tag extends BlogAppModel {
	var $name = 'Tag';

	var $order = array(
		'Tag.name' => 'ASC'
	);

	function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __('Please enter a tag', true)
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __('That tag already exists', true)
				)
			)
		);
	}

	var $hasAndBelongsToMany = array(
		'Post' => array(
			'className'              => 'Blog.Post',
			'joinTable'              => 'posts_tags',
			'with'                   => 'Blog.PostsTag',
			'foreignKey'             => 'tag_id',
			'associationForeignKey'  => 'post_id',
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

	function getCount($limit = 50) {
		$tags = Cache::read('tag_count');

		if ($tags !== false) {
			return $tags;
		}

		$tags = $this->find(
			'all',
			array(
				'fields' => array(
					'Tag.id',
					'Tag.name'
					),
				'contain' => array(
					'Post' => array(
						'fields' => array(
							'Post.id'
							)
						)
					),
				'limit' => $limit
				)
			);

		foreach($tags as $k => $tag) {
			$tags[$k]['Tag']['count'] = count($tag['Post']);
			unset($tags[$k]['Post']);
		}

		Cache::write('tag_count', $tags, 'blog');

		return $tags;
	}

	function findPostsByTag($tag) {
		$tags = $this->find(
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

		return Set::extract('/Post/id', $tags);
	}
}

?>