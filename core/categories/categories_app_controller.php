<?php
	/**
	 * @page Categoires-Plugin Categories plugin
	 *
	 * @section categories-overview What is it
	 *
	 * The categories plugin provides an easy way for developers to make data
	 * categorizable. This is done by adding a category_id to the table inquestion.
	 * When a category_id field is present the CategorisableBehavior is automatically
	 * attached through the Event system.
	 *
	 * @section categories-usage How to use it
	 *
	 * When you need to add categories to a row, after having added the category_id
	 * to the table you will need to include a dropdown for the user to select which
	 * category the row will belong to. The easy way to do this is to add the following
	 * snip of code to your view. That will take care of everything for you regarding
	 * the selection of a category.
	 *
	 * @code
	 *	echo $this->element('category_list', array('plugin' => 'Categories'));
	 * @endcode
	 *
	 * @section categories-code How it works
	 *
	 * With every request when models are being started up, an event is triggered
	 * by the Infinitas core. This event is a call to all plugins that need to
	 * attach behaviors to the models for the current request. At this point the
	 * CategoriesEvents will establish if the model being loaded has the category_id
	 * field and if so attach the CategorisableBehavior to the model. Once the
	 * behavior has been attached the startup method is called and the behavior will
	 * create the relations necessary for the model to be categorized.
	 *
	 * Before any finds the CategorisableBehavior will automatically contain
	 * the Category model so that the data will be available in the views.
	 *
	 * @image html sql_categories_plugin.png "Categories Plugin table structure"
	 *
	 * @section categories-see-also Also see
	 * @ref EventCore
	 */

	/**
	 * @brief Categorize your appliaction
	 * 
	 * The categories plugin makes categorising records in your application easy
	 * by automatically attaching and joing the related category information
	 * to models that require it.
	 *
	 * CategoriesAppController is the base controller class that other controllers
	 * extend from inside the categories plugin
	 *
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.Categories
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