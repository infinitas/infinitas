<?php
	/**
	 * Filter events.
	 * 
	 * events for the filter plugin
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package infinitas
	 * @subpackage infinitas.filter.events
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	 class FilterEvents extends AppEvents{
		 public function onRequireComponentsToLoad(){
			return array(
				'Filter.Filter' => array(
					'actions' => array('admin_index')
				)
			);
		 }
	 }