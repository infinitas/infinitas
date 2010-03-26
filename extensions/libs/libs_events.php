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
	}