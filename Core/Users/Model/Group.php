<?php
	/**
	 *
	 *
	 */
	class Group extends UsersAppModel {
		public $useTable = 'groups';

		public $actsAs1 = array('Tree', 'Acl' => array('requester'));

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __d('users', 'Please enter a name for this group')
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __d('users', 'There is alread a group with this name')
					)
				)
			);
		}

		public function parentNode() {
			return null;
		}
	}