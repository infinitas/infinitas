<?php
	/**
	 * CategoriesAppController is the base controller for the %Categories plugin
	 *
	 * The categories plugin makes categorising records in your application easy
	 * by automatically attaching and joing the related category information
	 * to models that require it. All the category related controllers extends
	 * from CategoriesAppController.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Categories
	 * @subpackage Infinitas.Categories.AppController
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class CategoriesAppController extends AppController {
		/**
		 * before anything starts processing this fires to include the filter
		 * helper
		 * 
		 * @access public
		 */
		public function beforeFilter(){
			parent::beforeFilter();
			$this->helpers[] = 'Filter.Filter';
		}
	}