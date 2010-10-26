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

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a name for the menu', true)
					),
					'validName' => array(
						'rule' => '/[a-z_]{1,100}/i',
						'message' => __('Please enter a name for the menu lower case letters and under-scores only', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a menu with that name', true)
					)
				),
				'type' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the menu type', true)
					),
					'validName' => array(
						'rule' => '/[a-z_]{1,100}/i',
						'message' => __('Please enter a valid type for the menu lower case letters and under-scores only', true)
					)
				)
			);
		}

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