<?php
	/**
	 * The Menu model.
	 * 
	 * This is just a way to store the names of the menu groups that have been
	 * created
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Menus
	 * @subpackage Infinitas.Menus.models.Menu
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class Menu extends MenusAppModel {
		public $useTable = 'menus';

		/**
		 * The relations for the menu
		 *
		 * @var array
		 * @access public
		 */
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

		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a name for the menu')
					),
					'validName' => array(
						'rule' => '/[a-z_]{1,100}/i',
						'message' => __('Please enter a name for the menu lower case letters and under-scores only')
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a menu with that name')
					)
				),
				'type' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the menu type')
					),
					'validName' => array(
						'rule' => '/[a-z_]{1,100}/i',
						'message' => __('Please enter a valid type for the menu lower case letters and under-scores only')
					)
				)
			);
		}
		
		public function save($data = null, $validate = true, $fieldList = array()) {
			$this->transaction();
			
			$saved = parent::save($data, $validate, $fieldList);
			
			if($saved && $this->MenuItem->hasContainer($this->id)) {
				$this->transaction(true);
			}
			
			else {
				$this->transaction(false);
			}
			
			return $saved;
		}

		/**
		 * If the menu is deleted, the menu items should also be deleted. As its a
		 * mptt tree deleting the root node will cause cake to delete everything
		 * within the tree and the whole thing will be gone
		 * 
		 * @access public
		 * 
		 * @return mixed what ever the parent returns
		 */
		public function afterDelete() {
			$menuItem = $this->MenuItem->find('first', array('conditions' => array('menu_id' => $this->id, 'parent_id' => null)));

			if(!empty($menuItem['MenuItem']['id'])) {
				$this->MenuItem->Behaviors->disable('Trashable');
				$this->MenuItem->delete($menuItem['MenuItem']['id']);
				$this->MenuItem->Behaviors->enable('Trashable');
			}

			return parent::afterDelete();
		}
	}