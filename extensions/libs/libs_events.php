<?php
	class LibsEvents extends AppEvents{
		public function onAttachBehaviors(&$event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				if (array_key_exists('locked', $event->Handler->_schema) && !$event->Handler->Behaviors->enabled('Libs.Lockable')) {
					$event->Handler->Behaviors->attach('Libs.Lockable');
				}

				if (array_key_exists('slug', $event->Handler->_schema) && !$event->Handler->Behaviors->enabled('Libs.Sluggable')) {
					$event->Handler->Behaviors->attach(
						'Libs.Sluggable',
						array(
							'label' => array($event->Handler->displayField)
						)
					);
				}

				if (array_key_exists('ordering', $event->Handler->_schema) && !$event->Handler->Behaviors->enabled('Libs.Sequence')) {
					$event->Handler->Behaviors->attach('Libs.Sequence');
				}

				if (array_key_exists('rating', $event->Handler->_schema) && !$event->Handler->Behaviors->enabled('Libs.Rateable')) {
					$event->Handler->Behaviors->attach('Libs.Rateable');
				}

				if (array_key_exists('comment_count', $event->Handler->_schema) && !$event->Handler->Behaviors->enabled('Libs.Commentable')) {
					$event->Handler->Behaviors->attach('Libs.Commentable');
				}

				if (array_key_exists('lft', $event->Handler->_schema) && array_key_exists('rght', $event->Handler->_schema) && !$event->Handler->Behaviors->enabled('Tree')) {
					$event->Handler->Behaviors->attach('Tree');
				}

				$noTrashModels = array('Session', 'SchemaMigration', 'Config', 'Aco', 'Aro', 'Trash');
				if (!in_array($event->Handler->name, $noTrashModels) && !isset($event->Handler->noTrash) && !$event->Handler->Behaviors->enabled('Libs.Trashable')) {
					$event->Handler->Behaviors->attach('Libs.Trashable');
				}
			}
		}

		public function onRequireComponentsToLoad(&$event){
			return array(
				'Libs.Infinitas',
				'Session','RequestHandler', 'Auth', 'Acl', 'Security', // core general things from cake
				'Libs.MassAction'
			);
		}

		public function onRequireHelpersToLoad(&$event){
			return array(
				'Html', 'Form', 'Javascript', 'Session', 'Time', // core general things from cake
				'Libs.Infinitas'				
			);
		}

		public function onRequireJavascriptToLoad(){
			return array(
				'/libs/js/3rd/jquery',
				'/libs/js/3rd/require',
				'/libs/js/infinitas'
			);
		}

		public function onRequireCssToLoad(){
			return '/libs/css/jquery_ui';
		}
	}