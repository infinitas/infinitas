<?php
	/**
	 *
	 */
	class Address extends ContactAppModel{
		public $name = 'Address';

		public $virtualFields = array(
		    'address' => 'CONCAT(Address.street, ", ", Address.city, ", ", Address.province)'
		);

		public $belongsTo = array(
			'Contact.Country'
		);

		public function getAddressByUser($user_id = null, $type = 'list'){
			if(!$user_id){
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
						'Address.foreign_key' => $user_id,
						'Address.plugin' => 'management',
						'Address.model' => 'user'
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