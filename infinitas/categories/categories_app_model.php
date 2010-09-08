<?php
	class CategoriesAppModel extends AppModel {
		public $tablePrefix = 'global_';
	/**
	 * Customized paginateCount method
	 *
	 * @param array
	 * @param integer
	 * @param array
	 * @return
	 * @access public
	 */
		public function paginateCount($conditions = array(), $recursive = 0, $extra = array()) {
			$parameters = compact('conditions');
			if ($recursive != $this->recursive) {
				$parameters['recursive'] = $recursive;
			}
			if (isset($extra['type']) && isset($this->_findMethods[$extra['type']])) {
				$extra['operation'] = 'count';
				return $this->find($extra['type'], array_merge($parameters, $extra));
			} else {
				return $this->find('count', array_merge($parameters, $extra));
			}
		}

	}