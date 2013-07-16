<?php
/**
 * GlobalCategory
 *
 * @package Infinitas.Contents.Model
 */

/**
 * GlobalCategory
 *
 * Category model handles the CRUD for categories.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contents.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class GlobalCategory extends ContentsAppModel {
/**
 * Make the records contentable
 *
 * @var boolean
 */
	public $contentable = true;

/**
 * Make the records lockable
 *
 * lockable enables the internal row locking while a row is being modified
 * preventing anyone from accessing that row.
 *
 * @var boolean
 */
	public $lockable = true;

/**
 * BelongsTo relations
 *
 * @var array
 */
	public $belongsTo = array(
		'Group' => array(
			'className' => 'Users.Group'
		)
	);

/**
 * HasOne relations
 *
 * @var array
 */
	public $hasOne = array(
		'GlobalContent' => array(
			'className' => 'Contents.GlobalContent',
			'foreignKey' => 'foreign_key',
			'conditions' => array(
				'GlobalContent.foreign_key = GlobalCategory.id',
				'GlobalContent.model' => 'Contents.GlobalCategory'
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
	public function  __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->order = array(
			$this->alias . '.lft' => 'asc'
		);

		$this->findMethods['getCategory'] = true;
		$this->findMethods['categoryList'] = true;
	}

/**
 * BeforeValidate callback
 *
 * @param array $options
 *
 * @return boolean
 */
	public function beforeValidate($options = array()) {
		if (empty($this->data[$this->alias]['parent_id'])) {
			$this->data[$this->alias]['parent_id'] = 0;
		}
		return parent::beforeValidate($options);
	}

/**
 * Get active category ids
 *
 * get active ids of the categories for use in other finds where you only
 * want the active rows according to what categories are active.
 *
 * @return array
 */
	public function getActiveIds() {
		$ids = $this->find('list', array(
			'fields' => array(
				$this->alias . '.id', $this->alias . '.id'
			),
			'conditions' => array(
				$this->alias . '.active' => 1
			)
		));

		return $ids;
	}

/**
 * overwrite childern method to allow finding by slug or name
 *
 * @param string $id the id of the parent
 * @param boolean $direct direct children only or all like grandchildren
 *
 * @todo seems like a bug here with uuid's
 *
 * @return array
 */
	public function children($id = null, $direct = false) {
		if (!$id || is_int($id)) {
			return parent::children($id, $direct);
		}

		$this->virtualFields['slug'] = 'GlobalContent.slug';
		$this->virtualFields['title'] = 'GlobalContent.title';
		$id = $this->field('id', array(
			'or' => array(
				'GlobalCategory.id' => $id,
				'slug' => $id,
				'title' => $id
			),
		));

		return parent::children($id, $direct);
	}

/**
 * Get a category record
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	public function _findGetCategory($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['limit'] = 1;

			$query['fields'] = array_merge(
				(array)$query['fields'],
				array(
					'ParentCategory.*',
					'ParentCategoryData.id',
					'ParentCategoryData.model',
					'ParentCategoryData.foreign_key',
					'ParentCategoryData.title',
					'ParentCategoryData.slug',
					'ParentCategoryData.introduction',
					'ParentCategoryData.canonical_url',
					'ParentCategoryData.global_category_id'
				)
			);

			$query['joins'][] = array(
				'table' => 'global_categories',
				'alias' => 'ParentCategory',
				'type' => 'LEFT',
				'foreignKey' => false,
				'conditions' => array(
					'ParentCategory.id = GlobalCategory.parent_id'
				)
			);
			$query['joins'][] = array(
				'table' => 'global_contents',
				'alias' => 'ParentCategoryData',
				'type' => 'LEFT',
				'foreignKey' => false,
				'conditions' => array(
					'ParentCategoryData.foreign_key = ParentCategory.id'
				)
			);
			return $query;
		}

		$results = current($results);

		if (!empty($results[$this->alias][$this->primaryKey])) {
			if (!empty($results['ParentCategory'][$this->primaryKey])) {
				$results['ParentCategory']['title'] = $results['ParentCategoryData']['title'];
				$results['ParentCategory']['slug'] = $results['ParentCategoryData']['slug'];
				$results['ParentCategory']['canonical_url'] = $results['ParentCategoryData']['canonical_url'];
				unset($results['ParentCategoryData']);
			}

			$results['CategoryContent'] = $this->GlobalContent->find('getRelationsCategory', $results[$this->alias][$this->primaryKey]);
		}

		return $results;
	}

/**
 * generate a category drop down tree
 *
 * @param string $state
 * @param array $query
 * @param array $results
 *
 * @return array
 */
	public function _findCategoryList($state, $query, $results = array()) {
		if ($state === 'before') {
			$query['fields'] = array_merge(
				(array)$query['fields'],
				array(
					'GlobalCategory.id',
				)
			);
			return $query;
		}
		$_active = __d('contents', 'Active');
		$_inactive = __d('contents', 'Inactive');

		$return = array($_active => array(), $_inactive => array());
		foreach ($results as $result) {
			$title = $result['GlobalCategory']['title'];
			if ($result['GlobalCategory']['path_depth']) {
				$title = sprintf('%s %s', str_repeat('-', $result['GlobalCategory']['path_depth']), $title);
			}

			if ($result['GlobalCategory']['active']) {
				$return[$_active][$result['GlobalCategory']['id']] = $title;
				continue;
			}

			$return[$_inactive][$result['GlobalCategory']['id']] = $title;
		}

		return $return;
	}

/**
 * AfterSave callback
 *
 * @param boolean $created
 *
 * @return boolean
 */
	public function afterSave($created) {
		$this->saveField(
			'path_depth',
			count($this->getPath($this->id)) - 1,
			array('callbacks' => false)
		);

		return parent::afterSave($created);
	}

}