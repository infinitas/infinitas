<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class ViewCounterComponent extends InfinitasComponent{
		/**
		 * settings for the component
		 */
		public $__settings = array(
			'actions' => array(
				'view'
			),
			'admin' => false
		);

		public function initialize(&$Controller, $settings = array()){
			if(!isset($Controller->{$Controller->modelClass})){
				// no model being used.
				return false;
			}

			$settings = array_merge($this->__settings, (array)$settings);

			$check =
				// dont tack anything in admin
				$settings['admin'] === false && 
				isset($Controller->params['admin']) &&
				$Controller->params['admin'] &&

				// only track actions that are set
				in_array($Controller->action, $settings['actions']);

			if($check){
				$Controller->{$Controller->modelClass}->Behaviors->attach('ViewCounter.Viewable');
			}
		}
	}