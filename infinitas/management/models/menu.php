<?php
	/**
	 *
	 *
	 */
	class Menu extends ManagementAppModel{
		var $name = 'Menu';

		var $hasMany = array(
			'MenuItem' => array(
	            'className'  => 'Management.MenuItem',
	            'foreignKey' => 'menu_id',
	            'conditions' => array(
	            	'MenuItem.status' => 1
	            ),
	            'dependent'  => true
	        )
		);
	}
?>