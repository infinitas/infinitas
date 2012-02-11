<?php
	/**
	 * The MenuItem model.
	 * 
	 * The MenuItem model handles the items within a menu, these are the indervidual
	 * links that are used to build up the menu required.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Menus
	 * @subpackage Infinitas.Menus.models.MenuItem
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class MenuItem extends MenusAppModel{
		/**
		 * The name of the class
		 *
		 * @var string
		 * @access public
		 */
		public $name = 'MenuItem';

		/**
		 * The relations for menu items
		 * @var array
		 * @access public
		 */
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

		/**
		 * the default order of the menu items is set to lft as that is the only
		 * way that MPTT records show the hierarchy
		 *
		 * @var array
		 * @access public
		 */
		public $order = array(
			'MenuItem.menu_id' => 'ASC',
			'MenuItem.lft' => 'ASC'
		);

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate1 = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of the menu item, this is what users will see', true)
					)
				),
				'link' => array(
					'validateEitherOr' => array(
						'rule' => array('validateEitherOr', array('link', 'plugin')),
						'message' => __('Please only use external link or the route', true)
					),
					'validUrl' => array(
						'rule' => 'validateUrlOrAbsolute',
						'message' => __('please use a valid url (absolute or full)', true)
					)
				),
				'plugin' => array(
					'validateOnlyOneFilledIn' => array(
						'rule' => array('validateEitherOr', array('link', 'plugin')),
						'message' => __('Please use the external link or the route', true)
					)
				),
				'force_frontend' => array(
					'validateNothingEitherOr' => array(
						'rule' => 'validateNothingEitherOr',
						'message' => __('You can only force one area of the site', true)
					)
				),
				'force_backend' => array(
					'validateNothingEitherOr' => array(
						'rule' => 'validateNothingEitherOr',
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
						'rule' => 'validateJson',
						'allowEmpty' => true,
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
		 * @access public
		 * 
		 * @return bool is it valid?
		 */
		public function validateEmptyOrCssClass($field){
			return strlen(current($field)) == 0 || preg_match('/-?[_a-zA-Z]+[_a-zA-Z0-9-]*/', current($field));
		}

		/**
		 * Set the parent_id for the menu item before saving so that the menu will
		 * be within the correct menu item group. This only applies to the root
		 * level menu items and not the sub items.
		 *
		 * @param bool $cascade not used
		 * @access public
		 *
		 * @return the parent method
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

		/**
		 * This will get an entire menu based on the type that is selected. The
		 * data will be cached so further requests do not require access to the
		 * database.
		 *
		 * @param string $type the menu that you want to pull
		 * @access public
		 *
		 * @return array the menu items that belong to the type you asked for
		 */
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
		 * not create one. Keeps the mptt structure together so that all the items
		 * are withing a containing record.
		 *
		 * @param string $id the id of the menu
		 * @param string $name the name of the menu
		 * @access public
		 *
		 * @return bool if there is a container, or sone was created.
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
			return (bool)$this->save($data);
		}
	}