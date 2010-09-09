<?php
	/**
	 *
	 *
	 */
	class Menu extends ManagementAppModel{
		var $name = 'Menu';

		var $hasMany = array(
			'MenuItem' => array(
	            'className'  => 'Management.MenuItem',
	            'foreignKey' => 'menu_id',
	            'conditions' => array(
	            	'MenuItem.active' => 1
	            ),
	            'dependent'  => true
	        )
		);

		function afterSave($created) {
			parent::afterSave($created);

			if($created == true && $this->MenuItem->find('count', array('conditions' => array('menu_id' => $this->id, 'parent_id' => 0)) == 0)) {
				$data = array('MenuItem' => array(
					'name' => $this->data['Menu']['name'],
					'menu_id' => $this->id,
					'parent_id' => 0,
					'active' => 0,
					'fake_item' => true
				));

				$this->MenuItem->create();
				$this->MenuItem->save($data);
			}
		}

		function afterDelete() {
			$menuItem = $this->MenuItem->find('first', array('conditions' => array('menu_id' => $this->id, 'parent_id' => 0)));

			$this->MenuItem->Behaviors->disable('Trashable');
			$this->MenuItem->delete($menuItem['MenuItem']['id']);
			$this->MenuItem->Behaviors->enable('Trashable');
		}
	}