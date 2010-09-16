<?php
	/**
	 *
	 *
	 */
	class Menu extends MenusAppModel{
		public $name = 'Menu';

		public $hasMany = array(
			'MenuItem' => array(
	            'className'  => 'Menus.MenuItem',
	            'foreignKey' => 'menu_id',
	            'conditions' => array(
	            	'MenuItem.active' => 1
	            ),
	            'dependent'  => true
	        )
		);

		public function beforeSave($options){
			parent::beforeSave($options);
			return $this->MenuItem->hasContainer($this->id, $this->data['Menu']['name']);
		}

		public function afterSave($created) {
			parent::afterSave($created);
		}

		public function afterDelete() {
			$menuItem = $this->MenuItem->find('first', array('conditions' => array('menu_id' => $this->id, 'parent_id' => 0)));

			$this->MenuItem->Behaviors->disable('Trashable');
			$this->MenuItem->delete($menuItem['MenuItem']['id']);
			$this->MenuItem->Behaviors->enable('Trashable');

			parent::afterDelete();
		}
	}