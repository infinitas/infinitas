<?php
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
						'counterCache' => $this->settings[$Model->alias]['counterCache'],
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
		public function generateCategoryList() {
			return ClassRegistry::init('Categories.Category')->generatetreelist();
		}
	}