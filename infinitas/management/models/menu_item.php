<?php
	/**
	 *
	 *
	 */
	class MenuItem extends ManagementAppModel{
		var $name = 'MenuItem';

		var $belongsTo = array(
			'Management.Menu',
			'Management.Group',
			/*'Parent' => array(
	            'className'     => 'Management.MenuItem',
	            'foreignKey'    => 'parent_id',
				'fields'     => array(
					'Parent.id',
					'Parent.name',
					'Parent.lft',
					'Parent.rght',
					'Parent.parent_id',
				)
			)*/
		);

		var $order = array(
			'MenuItem.menu_id' => 'ASC',
			'MenuItem.lft' => 'ASC'
		);

		function getMenu($type = null){
			if (!$type) {
				return false;
			}

			$menus = Cache::read('menu.'.$type, 'core');
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
						'Menu' => array(
							'fields' => array(
								'Menu.id',
								'Menu.name',
								'Menu.type',
								'Menu.active',
							)
						)
					)
				)
			);

			Cache::write('menu.'.$type, $menus, 'core');

			return $menus;
		}

		function afterSave($created) {
			parent::afterSave($created);

			Cache::delete('menu', 'core');

			return true;
		}

		function afterDelete() {
			parent::afterDelete();

			Cache::delete('menu', 'core');

			return true;
		}
	}