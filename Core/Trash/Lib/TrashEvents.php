<?php
	final class TrashEvents extends AppEvents{
		public function onAttachBehaviors($event) {
			if($event->Handler->shouldAutoAttachBehavior()) {
				$noTrashModels = array(
					'Session', 'SchemaMigration', 'Config',
					'Aco', 'Aro', 'Trash'
				);

				$check = !in_array($event->Handler->name, $noTrashModels) &&
					!isset($event->Handler->noTrash) &&
					!$event->Handler->Behaviors->enabled('Trash.Trashable');
				
				if ($check) {
					$event->Handler->Behaviors->attach('Trash.Trashable');
				}
			}
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Trash' => array('controller' => 'trash', 'action' => 'index')
			);

			return $menu;
		}
	}