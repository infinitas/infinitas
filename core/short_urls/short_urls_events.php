<?php
	/**
	 * Events for the Infinitas Short Urls
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.short_urls
	 * @subpackage Infinitas.short_urls.events
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	 final class ShortUrlsEvents extends AppEvents{
		public function onSetupRoutes(){
			Router::connect(
				'/s/:code',
				array(
					'plugin' => 'short_urls',
					'controller' => 'short_urls',
					'action' => 'go'
				),
				array(
					'pass' => array('code'),
					'code' => '[0-9a-zA-Z]+'
				)
			);
		}
	 }