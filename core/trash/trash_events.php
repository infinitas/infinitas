<?php
	class TrashEvents extends AppEvents{
		public function onAttachBehaviors(&$event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				$noTrashModels = array(
					'Session', 'SchemaMigration', 'Config',
					'Aco', 'Aro', 'Trash'
				);
				
				if (!in_array($event->Handler->name, $noTrashModels) && !isset($event->Handler->noTrash) && !$event->Handler->Behaviors->enabled('Trash.Trashable')) {
					$event->Handler->Behaviors->attach('Trash.Trashable');
				}
			}
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Trash' => array('controller' => 'trash', 'action' => 'index')
			);

			return $menu;
		}
	}