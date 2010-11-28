<?php
	/**
	 * @brief Category model handles the CRUD for categories.
	 *
	 * Saving and editing categories are done here.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Categories.models
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class Category extends CategoriesAppModel {
		/**
		 * The model name
		 * 
		 * @var string
		 * @access public
		 */
		public $name = 'Category';

		/**
		 * lockable enables the internal row locking while a row is being modified
		 * preventing anyone from accessing that row.
		 *
		 * @var bool
		 * @access public
		 */
		public $lockable = true;

		/**
		 * default order of the model is set to lft as its an mptt table
		 *
		 * @var array
		 * @access public
		 */
		public $order = array(
			'Category.lft' => 'asc'
		);

		/**
		 * the relations for the category model
		 *
		 * @var array
		 * @access public
		 */
		public $belongsTo = array(
			//'Parent' => array(
			//	'className' => 'Categories.Category',
			//	'counterCache' => true
			//),
			'Users.Group'
		);

		/** 
		 * @copydoc AppModel::__construct()
		 */
		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'title' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a title for this category', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a category with this title', true)
					)
				)
			);
		}

		/**
		 * get active ids of the categories for use in other finds where you only
		 * want the active rows according to what categories are active.
		 *
		 * @access public 
		 * 
		 * @return array list of ids for categories that are active
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
		 * 
		 * @param mixed $id the id of the parent
		 * @param bool $direct direct children only or all like grandchildren
		 * @access public
		 *
		 * @todo seems like a bug here with uuid's
		 * 
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