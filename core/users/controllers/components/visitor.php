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

	class VisitorComponent extends InfinitasComponent{
		public function initialize(&$controller, $settings = array()){
			$this->Controller =& $controller;

			if($this->Controller->Session->read('Auth.User.id')){
				$User = ClassRegistry::init('Users.User');
				$User->unbindModel(
					array(
						'belongsTo' => array_keys($User->belongsTo),
						'hasOne' => array_keys($User->hasOne)
					)
				);
				
				$User->updateAll(
					array('User.last_login' => '\''.date('Y-m-d H:i:s').'\''),
					array('User.id' => $this->Controller->Session->read('Auth.User.id'))
				);
			}
		}
	}