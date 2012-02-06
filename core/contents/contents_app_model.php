<?php
	/**
	 * @brief ContentsAppModel base model
	 *
	 * The model that all the Contents related models extends from
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Contents
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ContentsAppModel extends AppModel {
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