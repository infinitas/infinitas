<?php
	class TrashEvents extends AppEvents{
		public function onAttachBehaviors(Event $Event) {
			if($Event->Handler->shouldAutoAttachBehavior()) {
				$noTrashModels = array(
					'Session', 'SchemaMigration', 'Config',
					'Aco', 'Aro', 'Trash', 'Lock'
				);

				$check = !in_array($Event->Handler->name, $noTrashModels) &&
					!isset($Event->Handler->noTrash) &&
					!$Event->Handler->Behaviors->enabled('Trash.Trashable');

				if ($check) {
					$Event->Handler->Behaviors->attach('Trash.Trashable');
				}
			}
		}

		public function onAdminMenu(Event $Event) {
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site'),
				'Trash' => array('controller' => 'trash', 'action' => 'index')
			);

			return $menu;
		}
	}