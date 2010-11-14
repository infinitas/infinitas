<?php
	final class ContentsEvents extends AppEvents{
		public function onAdminMenu($event){
			$menu['main'] = array(
				'Contents' => array('plugin' => 'contents', 'controller' => 'global_contents', 'action' => 'index'),
				'Layouts' => array('plugin' => 'contents', 'controller' => 'global_layouts', 'action' => 'index'),
			);

			return $menu;
		}

		public function  onAttachBehaviors($event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (isset($event->Handler->contentable) && $event->Handler->contentable && !$event->Handler->Behaviors->enabled('Contents.Contentable')) {
					$event->Handler->Behaviors->attach('Contents.Contentable');
				}
			}
		}
	}