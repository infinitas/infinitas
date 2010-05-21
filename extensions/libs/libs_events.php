<?php
	/*
		$debug = array();
		foreach(debug_backtrace() as $backtrace){
			if (isset($backtrace['file']) && strstr($backtrace['file'], '_events.php')) {
				$debug[] = array(
					'file' => $backtrace['file'],
					'function' => $backtrace['function'],
					'line' => $backtrace['line'],
				);
			}
		}
		pr($debug);
	*/
	class LibsEvents{

		/**
		 * Load the default cache settings.
		 *
		 * allows all the parts of the app to set up any cache configs that are
		 * needed.
		 *
		 * Called in InfinitasComponent::initialize
		 *
		 * @return true
		 */
		function onSetupCache1(){
			return true;
		}

		/**
		 * Load config vars from the db.
		 *
		 * This gets all the config vars from the database and loads them in to the
		 * {#see Configure} class to be used later in the app
		 *
		 * Called in InfinitasComponent::initialize
		 *
		 * @todo load the users configs also.
		 *
		 * @return true
		 */
		function onSetupConfig1(){
			return true;
		}

		function onSetupThemeStart1(){
			return true;
		}

		function onAttachBehaviors(&$event) {
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
	}