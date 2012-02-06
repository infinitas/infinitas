<?php
	/**
	 * @brief Category model handles the CRUD for categories.
	 *
	 * Saving and editing categories are done here.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Contents.models
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class GlobalCategory extends ContentsAppModel {
		/**
		 * The model name
		 * 
		 * @var string
		 * @access public
		 */
		public $name = 'GlobalCategory';

		public $contentable = true;

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
			'Group' => array(
				'className' => 'Users.Group'
			)
		);

		public $hasOne = array(
			'GlobalContent' => array(
				'className' => 'Contents.GlobalContent',
				'foreignKey' => 'foreign_key',
				'conditions' => array(
					'GlobalContent.foreign_key = GlobalCategory.id',
					'GlobalContent.model' => 'Contents.GlobalCategory'
				)
			)
		);

		/** 
		 * @copydoc AppModel::__construct()
		 */
		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->order = array(
				$this->alias . '.lft' => 'asc'
			);
		}

		public function beforeValidate($options = array()) {
			if(empty($this->data[$this->alias]['parent_id'])) {
				$this->data[$this->alias]['parent_id'] = 0;
			}
			return parent::beforeValidate($options);
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
						$this->alias . '.id', $this->alias . '.id'
					),
					'conditions' => array(
						$this->alias . '.active' => 1
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
							'GlobalCategory.slug' => $id,
							'GlobalCategory.title' => $id
						),
					)
				)
			);

			if(isset($id['GlobalCategory']['id']) && !empty($id['GlobalCategory']['id'])){
				$id = $id['GlobalCategory']['id'];
			}

			return parent::children($id, $direct);
		}
	}