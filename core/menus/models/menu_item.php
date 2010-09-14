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
				'className' => 'Management.Group',
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
	}