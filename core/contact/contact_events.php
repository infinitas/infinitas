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

	final class ContactEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Contact',
				'description' => 'Display your contact details and allow users to contact you',
				'icon' => '/contact/img/icon.png',
				'author' => 'Infinitas'
			);
		}
		
		public function onRequireCssToLoad(&$event){
			return array(
				'/contact/css/contact'
			);
		}
	}