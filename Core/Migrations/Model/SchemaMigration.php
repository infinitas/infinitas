<?php
	/**
	 * SchemaMigration model
	 *
	 * @brief Add some documentation for SchemaMigration model.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link		http://infinitas-cms.org/Migrations
	 * @package		Migrations.models.SchemaMigration
	 * @license		http://infinitas-cms.org/mit-license The MIT License
	 * @since		0.9b1
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class SchemaMigration extends MigrationsAppModel {
		/**
		 * Additional behaviours that are attached to this model
		 *
		 * @access public
		 * @var array
		 */
		public $actsAs = array(
			// 'Libs.Feedable',
			// 'Libs.Rateable'
		);

		/**
		 * How the default ordering on this model is done
		 *
		 * @access public
		 * @var array
		 */
		public $order = array(
		);

		/**
		 * hasOne relations for this model
		 *
		 * @access public
		 * @var array
		 */
		public $hasOne = array(
		);

		/**
		 * belongsTo relations for this model
		 *
		 * @access public
		 * @var array
		 */
		public $belongsTo = array(
		);

		/**
		 * hasMany relations for this model
		 *
		 * @access public
		 * @var array
		 */
		public $hasMany = array(
		);

		/**
		 * hasAndBelongsToMany relations for this model
		 *
		 * @access public
		 * @var array
		 */
		public $hasAndBelongsToMany = array(
		);

		/**
		 * overload the construct method so that you can use translated validation
		 * messages.
		 *
		 * @access public
		 *
		 * @param mixed $id string uuid or id
		 * @param string $table the table that the model is for
		 * @param string $ds the datasource being used
		 *
		 * @return void
		 */
		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);
			
			$this->validate = array(
			);
		}
	}
