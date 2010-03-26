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
	* @link http://infinitas-cms.org
	* @package sort
	* @subpackage sort.comments
	* @license http://www.opensource.org/licenses/mit-license.php The MIT License
	* @since 0.5a
	*/
	class AppModel extends Model {
		/**
		* The database configuration to use for the site.
		*/
		var $useDbConfig = 'default';
		//var $tablePrefix = 'core_';

		/**
		* Behaviors to attach to the site.
		*/
		var $actsAs = array(
			'Containable',
			'Libs.Infinitas',
			'Libs.Lockable',
			'Libs.Logable',
			'Libs.SoftDeletable',
			'Events.Event'
			//'Libs.AutomaticAssociation'
		);

		/**
		* error messages in the model
		*/
		var $_errors = array();
	}
?>