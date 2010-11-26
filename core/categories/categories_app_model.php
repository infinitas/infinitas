<?php
	/**
	 * Categories base model
	 *
	 * The model that all the category related models extends from
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Categories
	 * @subpackage Infinitas.Categories.AppModel
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class CategoriesAppModel extends AppModel {
		/**
		 * the table prefix for this plugin
		 *
		 * @var string
		 * @access public
		 */
		public $tablePrefix = 'global_';

		/**
		 * Customized paginateCount method
		 * 
		 * @todo why is this needed
		 *
		 * @param array $conditions the conditions for the find
		 * @param int $recursive the recursive level
		 * @param string $extra 
		 * @access public
		 *
		 * @return int the count of records
		 */
		public function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
			$parameters = compact('conditions');
			if ($recursive != $this->recursive) {
				$parameters['recursive'] = $recursive;
			}

			if (isset($extra['type']) && isset($this->_findMethods[$extra['type']])) {
				$extra['operation'] = 'count';
				return $this->find($extra['type'], array_merge($parameters, $extra));
			}

			else {
				return $this->find('count', array_merge($parameters, $extra));
			}
		}
	}