<?php
	/**
	 * Comment Template.
	 *
	 * @todo Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://www.dogmatic.co.za
	 * @package sort
	 * @subpackage sort.comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class Route extends ManagementAppModel {
		var $name = 'Route';

		var $tablePrefix = 'core_';

		var $blockedPlugins = array(
			'DebugKit',
			'Filter',
			'Libs'
		);

		function getPlugins(){
			$plugins = Configure::listObjects('plugin');

			foreach($plugins as $plugin){
				if (!in_array($plugin, $this->blockedPlugins)){
					$return[Inflector::underscore($plugin)] = $plugin;
				}
			}

			return array(0 => 'None') + (array)$return;
		}
	}
?>