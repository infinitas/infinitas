<?php
/**
	* Comment Template.
	*
	* @todo Implement .this needs to be sorted out.
	*
	* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	*
	* Licensed under The MIT License
	* Redistributions of files must retain the above copyright notice.
	* @filesource
	* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	* @link http://infinitas-cms.org
	* @package sort
	* @subpackage sort.comments
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	* @since 0.5a
	*/

	class ViewableBehavior extends ModelBehavior {
		/**
		* Contain settings indexed by model name.
		*
		* @var array
		* @access private
		*/
		public $__settings = array();

		public $__session = array();

		/**
		* Initiate behavior for the model using specified settings.
		* Available settings:
		*
		* - view_counter: string :: the field in the table that has the count
		* - session_tracking false to disable, int for number of views to keep track of
		* 	views are tracked by displayField and will do funny things if displayField is not a string.
		*
		* @param object $Model Model using the behaviour
		* @param array $settings Settings to override for model.
		* @access public
		*/
		public function setup(&$Model, $settings = array()) {
			$default = array(
				'view_counter' => 'views',
				'session_tracking' => 20
			);

			if (!isset($this->__settings[$Model->alias])) {
				$this->__settings[$Model->alias] = $default;
			}

			$this->__settings[$Model->alias] = am($this->__settings[$Model->alias], ife(is_array($settings), $settings, array()));
		}

		/**
		* This happens after a find happens.
		*
		* @param object $Model Model about to be saved.
		* @return boolean true if save should proceed, false otherwise
		* @access public
		*/
		public function afterFind(&$Model, $data) {
			// skip finds with more than one result.
			if (isset($data[0]) && count($data) > 1) {
				return $data;
			}
			
			if(isset($this->__settings[$Model->alias]['session_tracking']) && $this->__settings[$Model->alias]['session_tracking']){
				$this->Session = new CakeSession();
				$this->__session[$Model->alias] = $this->Session->read('Viewable.'.$Model->alias);
			}

			if (isset($data[0][$Model->alias][$this->__settings[$Model->alias]['view_counter']])) {
				$data[0][$Model->alias][$this->__settings[$Model->alias]['view_counter']]++;
				$Model->{$Model->primaryKey} = $data[0][$Model->alias][$Model->primaryKey];

				if(!isset($__session[$Model->alias][$data[0][$Model->alias][$Model->displayField]])){
					$__data = array(
						$Model->primaryKey => $data[0][$Model->alias][$Model->primaryKey],
						$this->__settings[$Model->alias]['view_counter'] => $data[0][$Model->alias][$this->__settings[$Model->alias]['view_counter']],
						'modified' => false
					);

					$Model->save(
						$__data,
						array(
							'validate' => false,
							'callbacks' => false
						)

					);
				}

				unset($this->__session[$Model->alias][$data[0][$Model->alias][$Model->displayField]]);
				$this->__session[$Model->alias][$data[0][$Model->alias][$Model->displayField]] = $data[0][$Model->alias][$Model->primaryKey];
				if(count($this->__session[$Model->alias]) > $this->__settings[$Model->alias]['session_tracking']){
					array_shift($this->__session[$Model->alias]);
				}
				$this->Session->write('Viewable.'.$Model->alias, $this->__session[$Model->alias]);
			}

			return $data;
		}

		/**
		 * Get the most viewed records for the table
		 * 
		 * @param object $Model the model that is being used.
		 * @param int $limit the number or records to return
		 * 
		 * @return array the most viewed records
		 */
		public function getMostViewed(&$Model, $limit = 10){
			return $Model->find(
				'all',
				array(
					'fields' => array(
						$Model->alias.'.id',
						$Model->alias.'.'.$Model->displayField,
						$Model->alias.'.views'
					),
					'conditions' => array(
						$Model->alias.'.views > ' => 0
					),
					'order' => array(
						$Model->alias.'.views' => 'DESC'
					),
					'limit' => (int)$limit
				)
			);
		}
	}