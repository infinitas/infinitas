<?php
	/**
	 *
	 *
	 */
	class MenuItem extends MenusAppModel{
		public $name = 'MenuItem';

		public $belongsTo = array(
			'Menu' => array(
				'className' => 'Menus.Menu',
				'fields' => array(
					'Menu.id',
					'Menu.name',
					'Menu.type',
					'Menu.active',
				)
			),
			'Group' => array(
				'className' => 'Users.Group',
				'fields' => array(
					'Group.id',
					'Group.name'
				)
			)
		);

		public $order = array(
			'MenuItem.menu_id' => 'ASC',
			'MenuItem.lft' => 'ASC'
		);

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of the menu item, this is what users will see', true)
					)
				),
				'link' => array(
					'onlyOneFilledIn' => array(
						'rule' => 'onlyOneFilledIn',
						'message' => __('Please only use external link or the route', true)
					),
					'validUrl' => array(
						'rule' => 'validateUrlOrAbsolute',
						'message' => __('please use a valid url (absolute or full)', true)
					)
				),
				'plugin' => array(
					'onlyOneFilledIn' => array(
						'rule' => 'validateOnlyOneFilledIn',
						'message' => __('Please use the external link or the route', true)
					)
				),
				'force_frontend' => array(
					'forceOnlyOne' => array(
						'rule' => 'forceOnlyOne',
						'message' => __('You can only force one area of the site', true)
					)
				),
				'force_backend' => array(
					'forceOnlyOne' => array(
						'rule' => 'validateForceOnlyOne',
						'message' => __('You can only force one area of the site', true)
					)
				),
				'group_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the group that can see this link', true)
					)
				),
				'params' => array(
					'emptyOrJson' => array(
						'rule' => 'validateEmptyOrJson',
						'message' => __('Please enter some valid json or leave empty', true)
					)
				),
				'class' => array(
					'emptyOrValidCssClass' => array(
						'rule' => 'validateEmptyOrCssClass',
						'message' => __('Please enter valid css classes', true)
					)
				),
				'menu_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the menu this item belongs to', true)
					)
				),
				'parent_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select where in the menu this item belongs', true)
					)
				)
			);
		}

		/**
		 * Empty string or valid css class
		 *
		 * @param array $field the field being validated
		 * @return bool is it valid?
		 */
		public function validateEmptyOrCssClass($field){
			return strlen(current($field)) == 0 || preg_match('/-?[_a-zA-Z]+[_a-zA-Z0-9-]*/', current($field));
		}
		
		/**
		 * Only allow forcing the frontend or the backend, not both. both being
		 * empty for auto deciding by cake is fine also
		 *
		 * @param array $field not used
		 * @return bool is it valid?
		 */
		public function validateForceOnlyOne($field){
			return
				// both empty auto select
				empty($this->data['MenuItem']['force_frontend']) && empty($this->data['MenuItem']['force_backedn']) ||

				// force frontend
				empty($this->data['MenuItem']['force_frontend']) && !empty($this->data['MenuItem']['force_backend']) ||

				// force backend
				!empty($this->data['MenuItem']['force_frontend']) && empty($this->data['MenuItem']['force_frontend']);
		}

		/**
		 * only allow the user to fill in either the url manually or select from
		 * the dropdowns an internal link
		 *
		 * @param array $field not used
		 * @return bool is it valid
		 */
		public function validateOnlyOneFilledIn($field){
			return
				// using routing
				empty($this->data['MenuItem']['link']) &&
				!empty($this->data['MenuItem']['plugin'])

				||

				// using a direct link
				!empty($this->data['MenuItem']['link']) &&
				empty($this->data['MenuItem']['plugin']);
		}

		/**
		 * this can be a url relative to the site /my/page or full like
		 * http://site.com/my/page it can also be empty for times when the selects
		 * are used to build the url
		 *
		 * @param array $field the field being validated
		 * @return bool is it valid
		 */
		public function validateUrlOrAbsolute($field){
			return
				// not in use
				current($field) == '' ||

				// aboulute url
				substr(current($field), 0, 1) == '/' ||

				// valid url
				Validation::url(current($field), true);
		}

		/**
		 * before saving
		 *
		 * Set the parent_id for the menu item
		 */
		public function beforeSave($cascade){
			if($this->data['MenuItem']['parent_id'] == 0) {
				$menuItem = $this->find(
					'first',
					array(
						'fields' => array('id'),
						'conditions' => array(
							'parent_id' => 0,
							'menu_id' => $this->data['MenuItem']['menu_id']
						)
					)
				);
				$this->data['MenuItem']['parent_id'] = $menuItem['MenuItem']['id'];
			}

			return parent::beforeSave($cascade);
		}

		public function getMenu($type = null){
			if (!$type) {
				return false;
			}

			$menus = Cache::read($type, 'menus');
			if (!empty($menus)) {
				return $menus;
			}

			$menus = $this->find(
				'threaded',
				array(
					'fields' => array(
						'MenuItem.id',
						'MenuItem.name',
						'MenuItem.link',

						'MenuItem.prefix',
						'MenuItem.plugin',
						'MenuItem.controller',
						'MenuItem.action',
						'MenuItem.params',
						'MenuItem.force_backend',
						'MenuItem.force_frontend',

						'MenuItem.class',
						'MenuItem.active',
						'MenuItem.menu_id',
						'MenuItem.parent_id',
						'MenuItem.lft',
						'MenuItem.rght',
						'MenuItem.group_id',
					),
					'conditions' => array(
						'Menu.type' => $type,
						'Menu.active' => 1,
						'MenuItem.active' => 1,
						'MenuItem.parent_id !=' => 0
					),
					'contain' => array(
						'Menu'
					)
				)
			);

			Cache::write($type, $menus, 'menus');

			return $menus;
		}

		/**
		 * check if the menu being created has the menu_itmes container and if
		 * not create one.
		 */
		public function hasContainer($id, $name){
			$count = $this->find(
				'count',
				array(
					'conditions' => array(
						'menu_id' => $id,
						'parent_id' => 0
					)
				)
			);
			
			if($count > 0){
				return true;
			}

			$data = array(
				'MenuItem' => array(
					'name' => $name,
					'menu_id' => $this->id,
					'parent_id' => 0,
					'active' => 0,
					'fake_item' => true
				)
			);

			$this->create();
			return $this->save($data);
		}
	}