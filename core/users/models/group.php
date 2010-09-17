<?php
	/**
	 *
	 *
	 */
	class Group extends UsersAppModel{
		public $name = 'Group';
		public $actsAs1 = array('Tree', 'Acl' => array('requester'));

		public function parentNode() {
			return null;
		}
	}