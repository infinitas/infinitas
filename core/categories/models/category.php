<?php
	/**
	 * Category model.
	 *
	 * Saving and editing categories are done here.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package sort
	 * @subpackage sort.comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class Category extends CategoriesAppModel {
		public $name = 'Category';

		public $lockable = true;

		public $order = array(
			'Category.lft' => 'asc'
		);

		public $validate = array(
			'title' => array(
				'notempty' => array('rule' => array('notempty')),
			)
		);

		public $belongsTo = array(
			//'Parent' => array(
			//	'className' => 'Categories.Category',
			//	'counterCache' => true
			//),
			'User.Group'
		);

		/**
		 * get active categories
		 */
		public function getActiveIds(){
			$ids = $this->find(
				'list',
				array(
					'fields' => array(
						'Category.id', 'Category.id'
					),
					'conditions' => array(
						'Category.active' => 1
					)
				)
			);

			return $ids;
		}

		/**
		 * overwrite childern method to allow finding by slug or name
		 * @return TreeBehavior::children
		 */
		public function children($id = null, $direct = false){
			if(!$id || is_int($id)){
				return parent::children($id, $direct);
			}

			$id = $this->find(
				'first',
				array(
					'conditions' => array(
						'or' => array(
							'Category.slug' => $id,
							'Category.title' => $id
						),
					)
				)
			);

			if(isset($id['Category']['id']) && !empty($id['Category']['id'])){
				$id = $id['Category']['id'];
			}

			return parent::children($id, $direct);
		}
	}