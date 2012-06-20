<?php
	/**
	 * PublishableBehavior allows the use of datetime fields for start and end ranges on content.  Included
	 * functionality allows for checking published status, toggling to published / unpublished status, and
	 * adding conditions to a find to properly filter those results
	 *
	 * PHP versions 4 and 5
	 *
	 * Copyright 2009, Brightball, Inc. (http://www.brightball.com)
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright Copyright 2009, Brightball, Inc. (http://www.brightball.com)
	 * @link http://github.com/aramisbear/brightball-open-source/tree/master Brightball Open Source
	 * @lastmodified $Date: 2009-04-02 13:17:10 -0500 (Thu, 2 Apr 2009) $
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	class PublishableBehavior extends ModelBehavior {
		private $__settings = array();
		
		protected $_built = array();

		public function setup($Model, $settings = array()) {
			$default = array(
				'start_column' => 'begin_publishing',
				'end_column' => 'end_publishing'
			);

			if (!isset($this->__settings[$Model->alias])) $this->__settings[$Model->alias] = $default;
			$this->__settings[$Model->alias] = am($this->__settings[$Model->alias], !empty(is_array($settings)) ? $settings : array());

			$this->_checkColumn($Model, $this->__settings[$Model->alias]['start_column']);
			$this->_checkColumn($Model, $this->__settings[$Model->alias]['end_column']);
		}

		public function beforeFind($Model, $query) {
			if (!array_key_exists('published', $query)) return true;

			if ($query['published']) { // Return published
				$conditions = $this->publishConditions($Model, true);
				$query['conditions'] = Set::merge($query['conditions'], $conditions);
			}

			else { // Return unpublished
				$conditions = $this->publishConditions($Model, false);
				$query['conditions'] = Set::merge($query['conditions'], $conditions);
			}

			return $query;
		}

		function publishConditions($Model, $published = true) {
			if ($published) {
				return array(
					$Model->alias . '.' . $this->__settings[$Model->alias]['start_column'] . ' <=' => date("Y-m-d H:i:s"),
					'or' => array(
						$Model->alias . '.' . $this->__settings[$Model->alias]['end_column'] => null,
						$Model->alias . '.' . $this->__settings[$Model->alias]['end_column'] . ' >' => date("Y-m-d H:i:s")
					)
				);
			}
			
			return array(
				'or' => array(
					$Model->alias . '.' . $this->__settings[$Model->alias]['start_column'] . ' >' => date("Y-m-d H:i:s"),
					$Model->alias . '.' . $this->__settings[$Model->alias]['start_column'] => null,
					$Model->alias . '.' . $this->__settings[$Model->alias]['end_column'] . ' <=' => date("Y-m-d H:i:s")
				)
			);
		}

		function _checkColumn($Model, $column) {
			$col = $Model->schema($column);
			if (empty($col)) {
				trigger_error('Publishable Model "' . $Model->alias . '" is missing column: ' . $column);
			}
		}

		function isPublished($Model, $id = '') {
			if (empty($id)) {
				$id = $Model->id;
			}
			if (empty($id)) {
				return false;
			}

			$page = $Model->find(
				'first',
				array(
					'fields' => array($this->__settings[$Model->alias]['start_column'], $this->__settings[$Model->alias]['end_column']),
					'recursive' => - 1,
					'conditions' => array($Model->primaryKey => $id)
				)
			);

			$check = empty($page[$Model->alias][$this->__settings[$Model->alias]['start_column']]) ||
				strtotime($page[$Model->alias][$this->__settings[$Model->alias]['start_column']]) > time() || (
					!empty($page[$Model->alias][$this->__settings[$Model->alias]['end_column']]) &&
					strtotime($page[$Model->alias][$this->__settings[$Model->alias]['end_column']]) < time()
				);
			if ($check) {
				return false;
			}

			return true;
		}

		function publish($Model, $id = array()) {
			return $this->_publishing($Model, $id);
		}

		function unpublish($Model, $id = array()) {
			return $this->_publishing($Model, $id, false);
		}

		function _publishing($Model, $id = array(), $publish = true) {
			if (empty($id)) {
				if (!empty($Model->id)) {
					$id = $Model->id;
				}

				return false;
			}

			if (is_scalar($id)) {
				$id = array($id);
			}

			$fields = array();
			if ($publish) {
				$fields = array(
					$Model->alias . '.' . $this->__settings[$Model->alias]['start_column'] => date("'Y-m-d H:i:s'"),
					$Model->alias . '.' . $this->__settings[$Model->alias]['end_column'] => null
				);
			}
			else {
				$fields = array(
					$Model->alias . '.' . $this->__settings[$Model->alias]['start_column'] => null,
					$Model->alias . '.' . $this->__settings[$Model->alias]['end_column'] => null
				);
			}

			$Model->updateAll($fields, array($Model->alias . '.' . $Model->primaryKey => $id));

			return true;
		}
	}