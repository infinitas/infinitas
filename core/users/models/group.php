<?php
	/**
	 *
	 *
	 */
	class Group extends UsersAppModel{
		public $name = 'Group';
		public $actsAs1 = array('Tree', 'Acl' => array('requester'));

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a name for this group', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is alread a group with this name', true)
					)
				)
			);
		}

		public function parentNode() {
			return null;
		}
	}