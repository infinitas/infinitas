<?php
	/**
	 *
	 */
	class Address extends ManagementAppModel{
		var $name = 'Address';

		var $virtualFields = array(
		    'address' => 'CONCAT(Address.street, ", ", Address.city, ", ", Address.province)'
		);

		var $belongsTo = array(
			'Management.Country',
			//'Management.Continent'
		);

		function getAddressByUser($user_id = null, $type = 'list'){
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
	}