<?php
	/**
	 * Allows any model to have categories.
	 *
	 * Add the field category_id to your model to have it all auto bind the
	 * behaviors and relate the models
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.categories
	 * @subpackage Infinitas.categories.behaviors.categorisable
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 */

	class CategorisableBehavior extends ModelBehavior {
		/**
		 * Contain settings indexed by model name.
		 *
		 * @var array
		 * @access private
		 */
		public $settings = array();

		protected $_defaults = array(
			'categoryAlias' => 'Category',
			'categoryClass' => 'Categories.Category',
			'foreignKey' => 'category_id',
			'counterCache' => 'item_count',
			'unsetInAfterFind' => false,
			'resetBinding' => false
		);

		/**
		 * Initiate behaviour for the model using settings.
		 *
		 * @param object $Model Model using the behaviour
		 * @param array $settings Settings to override for model.
		 * @access public
		 */
		public function setup(&$Model, $settings = array()) {
			$this->settings[$Model->alias] = array_merge($this->_defaults, $settings);

			$Model->bindModel(array(
				'belongsTo' => array(
					$this->settings[$Model->alias]['categoryAlias'] => array(
						'className' => $this->settings[$Model->alias]['categoryClass'],
						'foreignKey' => $this->settings[$Model->alias]['foreignKey'],
						//'counterCache' => $this->settings[$Model->alias]['counterCache'],
						'fields' => array(
							'Category.id',
							'Category.title',
							'Category.slug',
							'Category.active',
							'Category.group_id',
							'Category.parent_id'
						)
					)
				),
			), $this->settings[$Model->alias]['resetBinding']);
		}

		/**
		 * get a list of categories.
		 *
		 * @return array nested list of categories. TreeBehavior::generatetreelist
		 */
		public function generateCategoryList(&$Model) {
			return $Model->Category->generatetreelist();
			//return ClassRegistry::init('Categories.Category')->generatetreelist();
		}

		/**
		 * @todo special counterCache that finds relations and counts them all
		 *
		 * find all models that are using the category plugin, count the rows in
		 * each and then save that as the count.
		 *
		 * @param object $Model the model being worked with
		 * @param bool $created if the row is new or updated
		 * @return bool true
		 */
		public function afterSave($Model, $created) {
			return parent::afterSave($Model, $created);
		}
	}