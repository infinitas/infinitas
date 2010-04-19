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
				$Model = $event->Handler;
				
				if (array_key_exists('locked', $Model->_schema)) {
					$Model->Behaviors->attach('Libs.Lockable');
				}

				/*if (array_key_exists('deleted', $Model->_schema)) {
					$Model->Behaviors->attach('Libs.SoftDeletable');
				}*/

				if (array_key_exists('slug', $Model->_schema)) {
					$Model->Behaviors->attach('Libs.Sluggable');
				}

				if (array_key_exists('ordering', $Model->_schema)) {
					$Model->Behaviors->attach('Libs.Sequence');
				}

				if (array_key_exists('rating', $Model->_schema)) {
					$Model->Behaviors->attach('Libs.Rateable');
				}

				if (array_key_exists('comment_count', $Model->_schema)) {
					$Model->Behaviors->attach('Libs.Commentable');
				}

				if (array_key_exists('lft', $Model->_schema) && array_key_exists('rght', $Model->_schema) && !$Model->Behaviors->attached('Tree')) {
					$Model->Behaviors->attach('Libs.Tree');
				}

				$noTrashModels = array('Session', 'SchemaMigration', 'Config', 'Aco', 'Aro');
				if (!in_array($Model->name, $noTrashModels)) {
					$Model->Behaviors->attach('Libs.Trashable');
				}
			}
		}
	}