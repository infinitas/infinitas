<?php
	final class CategoriesEvents extends AppEvents{
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

		public function onSiteMapRebuild(&$event){
			$Category = ClassRegistry::init('Categories.Category');
			$newest = $Category->getNewestRow();
			$frequency = $Category->getChangeFrequency();

			$return = array();
			$return[] = array(
				'url' => Router::url(array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'index', 'admin' => false, 'prefix' => false), true),
				'last_modified' => $newest,
				'change_frequency' => $frequency
			);

			$categories = $Category->find('list');
			foreach($categories as $category){
				$return[] = array(
					'url' => Router::url(array('plugin' => 'categories', 'controller' => 'categories', 'action' => 'view', 'slug' => $category, 'admin' => false, 'prefix' => false), true),
					'last_modified' => $newest,
					'change_frequency' => $frequency
				);
			}

			return $return;
		}
	}