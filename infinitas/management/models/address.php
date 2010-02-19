<?php
	/**
	 *
	 *
	 */
	class Address extends ManagementAppModel{
		var $name = 'Address';

		var $belongsTo = array(
			'Management.Country'
		);
	}
?>