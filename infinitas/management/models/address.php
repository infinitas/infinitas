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
			'Management.Country'
		);
	}