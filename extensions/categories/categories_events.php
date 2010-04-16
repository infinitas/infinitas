<?php
	class CategoriesEvents {
		function onAttachBehaviors(&$event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				$Model = $event->Handler;
				
				if (array_key_exists('category_id', $Model->_schema)) {
					$Model->Behaviors->attach('Categories.Categorisable');
				}
			}
		}
	}