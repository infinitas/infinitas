<?php
/**
 * MenuItem
 *
 * The MenuItem model handles the items within a menu, these are the indervidual
 * links that are used to build up the menu required.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Menus.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

/**
 * MenuItem
 *
 * @package Infinitas.Menus.Model
 *
 * @property Menu $Menu
 * @property Group $Group
 */

class MenuItem extends MenusAppModel {

/**
 * Custom find methods
 *
 * @var array
 */
	public $findMethods = array(

	);

/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'Menu' => array(
			'className' => 'Menus.Menu',
			'fields' => array(
				'Menu.id',
				'Menu.name',
				'Menu.type',
				'Menu.active',
			),
			'counterCache' => 'item_count',
			'counterScope' => array(
				'MenuItem.parent_id IS NOT NULL',
				'MenuItem.active' => true
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
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->order = array(
			$this->alias . '.menu_id' => 'ASC',
			$this->alias . '.lft' => 'ASC'
		);

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('menus', 'Please enter the name of the menu item, this is what users will see'),
					'required' => true
				)
			),
			'link' => array(
				'validateEitherOr' => array(
					'allowEmpty' => true,
					'rule' => array('validateEitherOr', array('link', 'plugin')),
					'message' => __d('menus', 'Please only use external link or the route'),
					'required' => true
				),
				'validUrl' => array(
					'rule' => 'validateUrlOrAbsolute',
					'message' => __d('menus', 'please use a valid url (absolute or full)')
				)
			),
			'plugin' => array(
				'validateEitherOr' => array(
					'rule' => array('validateEitherOr', array('link', 'plugin')),
					'message' => __d('menus', 'Please use the external link or the route')
				)
			),
			'force_frontend' => array(
				'validateEitherOr' => array(
					'rule' => array('validateEitherOr', array('force_backend', 'force_frontend')),
					'allowEmpty' => true,
					'message' => __d('menus', 'You can only force one area of the site')
				)
			),
			'force_backend' => array(
				'validateEitherOr' => array(
					'rule' => array('validateEitherOr', array('force_backend', 'force_frontend')),
					'allowEmpty' => true,
					'message' => __d('menus', 'You can only force one area of the site')
				)
			),
			'group_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('menus', 'Please select the group that can see this link'),
					'required' => true
				),
				'validateRecordExists' => array(
					'rule' => 'validateRecordExists',
					'allowEmpty' => true,
					'message' => __d('menus', 'The selected group is not valid')
				)
			),
			'params' => array(
				'emptyOrJson' => array(
					'rule' => 'validateJson',
					'allowEmpty' => true,
					'message' => __d('menus', 'Please enter some valid json or leave empty')
				)
			),
			'class' => array(
				'emptyOrValidCssClass' => array(
					'rule' => 'validateEmptyOrCssClass',
					'message' => __d('menus', 'Please enter valid css classes')
				)
			),
			'menu_id' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('menus', 'Please select the menu this item belongs to'),
					'required' => true
				)
			),
			'parent_id' => array(
				'validateRecordExists' => array(
					'rule' => 'validateRecordExists',
					'allowEmpty' => true,
					'message' => __d('menus', 'The parent menu item is not valid')
				)
			)
		);
	}

/**
 * Empty string or valid css class
 *
 * @param array $field the field being validated
 *
 * @return boolean
 */
	public function validateEmptyOrCssClass($field) {
		$field = current($field);
		if (empty($field) && $field !== 0) {
			return true;
		}

		$matches = array();
		preg_match('/-?[_a-zA-Z]+[_a-zA-Z0-9-]*/', $field, $matches);

		return current($matches) === $field;
	}

/**
 * Set the parent_id for the menu item before saving so that the menu will
 * be within the correct menu item group. This only applies to the root
 * level menu items and not the sub items.
 *
 * @param bool $cascade not used
 *
 * @return the parent method
 */
	public function beforeValidate($options = array()) {
		$foreignKey = $this->belongsTo[$this->Menu->alias]['foreignKey'];

		if (!empty($this->data[$this->alias][$foreignKey]) && empty($this->data[$this->alias]['parent_id'])) {
			$menuItem = $this->find('first', array(
				'fields' => array(
					$this->alias . '.' . $this->primaryKey
				),
				'conditions' => array(
					$this->alias . '.parent_id' => null,
					$this->alias . '.' . $foreignKey => $this->data[$this->alias][$foreignKey]
				)
			));

			if (empty($menuItem[$this->alias]['id'])) {
				return false;
			}

			$this->data[$this->alias]['parent_id'] = $menuItem[$this->alias]['id'];
		}

		return parent::beforeValidate($options);
	}

/**
 * Get a specific menu for display
 *
 * This will get an entire menu based on the type that is selected. The
 * data will be cached so further requests do not require access to the
 * database.
 *
 * @param string $type the menu that you want to pull
 *
 * @return array
 */
	public function getMenu($type) {
		$menus = $this->find('threaded', array(
			'fields' => array(
				$this->alias . '.id',
				$this->alias . '.name',
				$this->alias . '.link',

				$this->alias . '.prefix',
				$this->alias . '.plugin',
				$this->alias . '.controller',
				$this->alias . '.action',
				$this->alias . '.params',
				$this->alias . '.force_backend',
				$this->alias . '.force_frontend',

				$this->alias . '.class',
				$this->alias . '.active',
				$this->alias . '.menu_id',
				$this->alias . '.parent_id',
				$this->alias . '.lft',
				$this->alias . '.rght',
				$this->alias . '.group_id',
			),
			'conditions' => array(
				$this->alias . '.parent_id != ' => null,
				$this->alias . '.active' => 1,
				$this->Menu->alias . '.active' => 1,
				$this->Menu->alias . '.type' => $type,
				'or' => array(
					$this->alias . '.group_id' => array(
						null,
						0,
						AuthComponent::user('group_id')
					)
				)
			),
			'joins' => array(
				$this->autoJoinModel($this->Menu->fullModelName())
			)
		));

		foreach ($menus as &$menu) {
			$this->__underscore($menu);
		}

		return $menus;
	}

/**
 * convert the camelcase stuff to underscored so links work properly
 *
 * @param array $menu the row of menu data
 *
 * @return void
 */
	private function __underscore(&$menu) {
		$menu[$this->alias]['plugin'] = Inflector::underscore($menu[$this->alias]['plugin']);
		$menu[$this->alias]['controller'] = substr(Inflector::underscore($menu[$this->alias]['controller']), 0, -11);
		$menu[$this->alias]['params'] = (array)json_decode($menu[$this->alias]['params'], true);

		if (!empty($menu['children'])) {
			foreach ($menu['children'] as &$child) {
				$this->__underscore($child);
			}
		}
	}

/**
 * Check if the menu has a container
 *
 * check if the menu being created has the menu_itmes container and if
 * not create one. Keeps the mptt structure together so that all the items
 * are withing a containing record.
 *
 * @param string $id the id of the menu
 * @param string $name the name of the menu
 *
 * @return boolean
 */
	public function hasContainer($menuId, $name = 'ROOT') {
		$count = $this->find('count', array(
			'conditions' => array(
				'menu_id' => $menuId,
				'parent_id' => null
			)
		));

		if ($count > 0) {
			return true;
		}

		$this->create();
		return (bool)$this->save(array(
			'name' => (string)$name,
			'menu_id' => $menuId,
			'parent_id' => null,
			'link' => '/',
			'group_id' => 0,
			'fake_item' => true
		), array('validate' => false));
	}

/**
 * get the menus for the ajax select in the backend
 *
 * @param string $menuId the menu to look up
 *
 * @return array
 */
	public function getParents($menuId) {
		return $this->generateTreeList(array(
			$this->alias . '.menu_id' => $menuId
		));
	}
}