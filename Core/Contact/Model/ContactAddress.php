<?php
	/**
	 *
	 */
	class ContactAddress extends ContactAppModel {
		/**
		 * the table prefix for this plugin
		 *
		 * @var string
		 * @access public
		 */
		public $tablePrefix = '';

		public $virtualFields = array(
			'address' => 'CONCAT(ContactAddress.street, ", ", ContactAddress.city, ", ", ContactAddress.province)'
		);

		public $belongsTo = array(
			'Contact.Country'
		);

		public function getAddressByUser($userId = null, $type = 'list'){
			if(!$userId){
				return false;
			}

			$contain = array();
			if($type === 'all'){
				$contain = array(
					'Country'
				);
			}

			$address = $this->find(
				$type,
				array(
					'conditions' => array(
						'ContactAddress.foreign_key' => $userId,
						'ContactAddress.plugin' => 'management',
						'ContactAddress.model' => 'user'
					),
					'contain' => $contain
				)
			);

			return $address;
		}

		/**
		 * Find a list of addresses for the currently selected plugin that may
		 * be related to what they user is looking for.
		 *
		 * @param array $conditions the conditions to search for
		 * @return array list of addresses
		 */
		public function getAddressesByRelated($conditions = array()){
			return $this->find(
				'list',
				array(
					'conditions' => (array)$conditions
				)
			);
		}
	}