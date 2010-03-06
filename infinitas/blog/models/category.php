<?php
	/**
	 * Blog Category model
	 *
	 * Defines relations and validation for the categories in the blog
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package blog
	 * @subpackage blog.models.category
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class Category extends BlogAppModel{
		var $name = 'Category';

		var $hasMany = array(
			'Blog.Post'
		);

		var $belongsTo = array(
			'Management.Group'
		);

		function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of your category', true)
					)
				)
			);
		}

		function getActiveIds(){
			$ids = Cache::read('active_category_ids', 'blog');
			if (!empty($ids)) {
				return $ids;
			}

			$ids = $this->find(
				'list',
				array(
					'fields' => array(
						'Category.id',
						'Category.id'
					),
					'conditions' => array(
						'Category.active' => 1
					)
				)
			);

			Cache::write('active_category_ids', $ids, 'blog');

			return $ids;
		}

		function afterSave($created) {
			parent::afterSave($created);

			Cache::delete('active_category_ids', 'blog');

			return true;
		}

		function afterDelete() {
			parent::afterDelete();

			Cache::delete('active_category_ids', 'blog');

			return true;
		}
	}
?>