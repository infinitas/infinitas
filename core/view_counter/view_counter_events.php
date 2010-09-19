<?php
	/**
	 * Events for the views behavior
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas
	 * @subpackage Infinitas.views.events
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	final class ViewCounterEvents extends AppEvents{
		public function onRequireComponentsToLoad(){
			return array(
				'ViewCounter.ViewCounter'
			);
		}
	}