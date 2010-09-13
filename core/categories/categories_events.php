<?php
	final class CategoriesEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Categories',
				'description' => 'Categorize your content',
				'icon' => '/categories/img/icon.png',
				'author' => 'Infinitas'
			);
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Categories' => array('controller' => false, 'action' => false),
				'Active' => array('controller' => 'categories', 'action' => 'index', 'Category.active' => 1),
				'Disabled' => array('controller' => 'categories', 'action' => 'index', 'Category.active' => 0)
			);

			return $menu;
		}
		
		function onAttachBehaviors(&$event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (array_key_exists('category_id', $event->Handler->_schema)  && !$event->Handler->Behaviors->enabled('Categories.Categorisable')) {
					$event->Handler->Behaviors->attach('Categories.Categorisable');
				}
			}
		}
	}