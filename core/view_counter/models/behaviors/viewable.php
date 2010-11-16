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
			if (empty($data) || isset($data[0][0]['count']) || isset($data[0]) && count($data) > 1 || !isset($data[0][$Model->alias][$Model->primaryKey])) {
				return $data;
			}
			
			if(isset($this->__settings[$Model->alias]['session_tracking']) && $this->__settings[$Model->alias]['session_tracking']){
				$this->Session = new CakeSession();
				$this->__session[$Model->alias] = $this->Session->read('Viewable.'.$Model->alias);
			}

			$user_id = $this->Session->read('Auth.User.id');
			$view['ViewCount'] = array(
				'user_id' => $user_id > 0 ? $user_id : 0,
				'model' => Inflector::camelize($Model->plugin).'.'.$Model->name,
				'foreign_key' => $data[0][$Model->alias][$Model->primaryKey]
			);
			
			$location = EventCore::trigger($this, 'geolocation.getLocation');
			$location = current($location['getLocation']);

			foreach($location as $k => $v){
				$view['ViewCount'][$k] = $v;
			}
			$view['ViewCount']['year'] = date('Y');
			$view['ViewCount']['month'] = date('m');
			$view['ViewCount']['day'] = date('j');
			$view['ViewCount']['day_of_year'] = date('z');
			$view['ViewCount']['week_of_year'] = date('W');
			$view['ViewCount']['hour'] = date('G'); // no leading 0

			/**
			 * http://dev.mysql.com/doc/refman/5.1/en/date-and-time-functions.html#function_dayofweek
			 * sunday is 1, php uses 0
			 */
			$view['ViewCount']['day_of_week'] = date('w') + 1;
			
			$Model->ViewCount->save($view);
			return $data;
		}
	}