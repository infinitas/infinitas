<?php
	/**
	 * User Model.
	 *
	 * Model for managing users
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package management
	 * @subpackage management.models.user
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7alpha
	 *
	 * @author Carl Sutton (dogmatic69)
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class User extends ManagementAppModel{
		var $name = 'User';

		var $tablePrefix = 'core_';

		var $hasOne = array();

		function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'username' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter your username', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('That username is taken, sorry', true)
					)
				),
				'email' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter your email address', true)
					),
					'email' => array(
						'rule' => array('email', true),
						'message' => __('That email address does not seem to be valid', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('It seems you are already registered, please use the forgot password option', true)
					)
				)
			);

			 $this->bindModel(
		        array('hasMany' => array(
		                'UserConfig' => array(
		                    'className' => 'UserConfig'
		                )
		            )
		        )
		    );

		}
	}
?>