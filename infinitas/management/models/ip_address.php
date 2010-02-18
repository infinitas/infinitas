<?php
	/**
	 * The model for ip address blocking.
	 *
	 * Used to manage ip addresses that are blocked
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package management
	 * @subpackage management.models.ip_address
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class IpAddress extends ManagementAppModel{
		var $name = 'IpAddress';

		var $displayField = 'ip_address';


		/**
		 * Get the blocked ip addresses / ranges.
		 *
		 * gets a list of ip addresses that are blocked and Caches the data so
		 * there is not so much queries.
		 *
		 * @return array of blocked ip addresses and blocked ranges.
		 */
		function getBlockedIpAddresses(){
			$ip_addresses = Cache::read('blocked_ips', 'core');
			if ($ip_addresses) {
				return $ip_addresses;
			}

			$ip_addresses = $this->find(
				'list',
				array(
					'conditions' => array(
						'IpAddress.active' => 1
					)
				)
			);

			Cache::write('blocked_ips', $ip_addresses, 'core');

			return $ip_addresses;
		}
	}
?>