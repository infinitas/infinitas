<?php
	/**
	 *
	 *
	 */
	class Group extends UsersAppModel{
		var $name = 'Group';
		var $actsAs1 = array('Tree', 'Acl' => array('requester'));

		function parentNode() {
			return null;
		}
	}