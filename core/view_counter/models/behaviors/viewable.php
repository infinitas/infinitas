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
			if (empty($data) || isset($data[0][0]['count']) || isset($data[0]) && count($data) > 1) {
				return $data;
			}
			
			if(isset($this->__settings[$Model->alias]['session_tracking']) && $this->__settings[$Model->alias]['session_tracking']){
				$this->Session = new CakeSession();
				$this->__session[$Model->alias] = $this->Session->read('Viewable.'.$Model->alias);
			}

			$view['ViewCount'] = array(
				'ip_address' => $this->Session->read('Auth.User.ip_address'),
				'user_id' => $this->Session->read('Auth.User.id'),
				'model' => Inflector::camelize($Model->plugin).'.'.$Model->name,
				'foreign_key' => $data[0][$Model->alias][$Model->primaryKey]
			);

			$Model->ViewCount->save($view);

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
			$fields = array(
				$Model->alias.'.id',
				$Model->alias.'.'.$Model->displayField,
				$Model->alias.'.views'
			);

			if($Model->hasField('slug')){
				$fields[] = $Model->alias.'.slug';
			}

			$rows = $Model->find(
				'all',
				array(
					'fields' => $fields,
					'conditions' => array(
						$Model->alias.'.views > ' => 0
					),
					'order' => array(
						$Model->alias.'.views' => 'DESC'
					),
					'limit' => (int)$limit
				)
			);

			return $rows;
		}

		/**
		 * Get the total number of views for a selected model
		 *
		 * Short cut method for models using the viewable behavior to get the
		 * total number of views for that model.
		 * 
		 * @return int the number of rows found
		 */
		public function getToalViews(&$Model){
			return $Model->ViewCount->getToalViews($Model->plugin.'.'.$Model->alias);
		}
	}