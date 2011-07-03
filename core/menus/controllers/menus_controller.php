<?php
	/**
	 * The MenuItem model handles the items within a menu, these are the indervidual
	 * links that are used to build up the menu required.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Menus
	 * @subpackage Infinitas.Menus.controllers.MenusController
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class MenusController extends MenusAppController{
		/**
		 * The controller name
		 * 
		 * @var string
		 * @access public
		 */
		public $name = 'Menus';
		
		public function admin_index(){
			$menus = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'type',
				'active' => (array)Configure::read('CORE.active_options')
			);

			$this->set(compact('menus', 'filterOptions'));
		}
	}