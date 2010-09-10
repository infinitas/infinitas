<?php
	/**
	 * Newsletter events
	 * 
	 * events for the newsletter system
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package newsletter
	 * @subpackage infinitas.newsletter.events
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	 final class NewsletterEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Newsletter',
				'description' => 'Keep in contact with your user base',
				'icon' => '/newsletter/img/icon.png',
				'author' => 'Infinitas'
			);
		}

		public function onSetupConfig(){
			return Configure::load('newsletter.config');
		}
		 
		public function onRequireComponentsToLoad(){
			return 'Newsletter.Emailer';
		}
	 }
