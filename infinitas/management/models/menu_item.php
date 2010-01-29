<?php
	/**
	 *
	 *
	 */
	class MenuItem extends ManagementAppModel{
		var $name = 'MenuItem';

		var $tablePrefix = 'core_';

		var $actsAs = array('Tree');

		var $belongsTo = array(
			'Management.Menu',
			'Management.Group'
		);

		var $order = array(
			'MenuItem.menu_id' => 'ASC',
			'MenuItem.lft' => 'ASC'
		);

		function getMenu($type = null){
			if (!$type) {
				return false;
			}

			$menus = Cache::read('menu_'.$type);
			if (!empty($menus)) {
				//return $menus;
			}

			$menus = $this->find(
				'threaded',
				array(
					'fields' => array(
						'Menu.id',
						'Menu.name',
						'Menu.type',
						'Menu.active',

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
						'MenuItem.active' => 1
					)
				)
			);

			Cache::write('menu_'.$type, $menus);

			return $menus;
		}
	}
?>