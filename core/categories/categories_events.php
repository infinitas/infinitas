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
		
		function onAttachBehaviors(&$event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (array_key_exists('category_id', $event->Handler->_schema)  && !$event->Handler->Behaviors->enabled('Categories.Categorisable')) {
					$event->Handler->Behaviors->attach('Categories.Categorisable');
				}
			}
		}
	}