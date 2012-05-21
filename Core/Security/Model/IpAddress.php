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

	class IpAddress extends ManagementAppModel {
		public $useTable = 'core_ip_addresses';

		public $displayField = 'ip_address';

		/**
		 * Get the blocked ip addresses / ranges.
		 *
		 * gets a list of ip addresses that are blocked and Caches the data so
		 * there is not so much queries.
		 *
		 * @return array of blocked ip addresses and blocked ranges.
		 */
		public function getBlockedIpAddresses(){
			$ipAddresses = Cache::read('blocked_ips', 'core');
			if ($ipAddresses) {
				return $ipAddresses;
			}

			$ipAddresses = $this->find(
				'list',
				array(
					'conditions' => array(
						$this->alias . '.active' => 1
					)
				)
			);

			Cache::write('blocked_ips', $ipAddresses, 'core');

			return $ipAddresses;
		}

		/**
		 * Check for other simmilar failed logins.
		 *
		 * This method will see how many other atempts that failed for that login
		 * exists, so things like "admin" or "administrator" will be more common
		 * as hackers always try them. things like this pose a higher risk so they will
		 * get a higher risk factor.
		 *
		 * The number of sessions is also taken into account so that sites will millions
		 * of users and only a few matches will not be a high risk, basicaly its
		 * proportional to the number of users of the site.
		 *
		 * The risk is later used to work out the amount of time the user will be
		 * blocked for.
		 *
		 * @param mixed $ipAddress the users ip address
		 * @param string $username the user name that was used to try login
		 *
		 * @return int the risk of the failed login.
		 */
		public function findSimmilarAttempts($ipAddress = null, $username = ''){
			if (!$ipAddress) {
				exit;
			}

			$badCount = $this->find(
				'count',
				array(
					'conditions' => array(
						$this->alias . '.description LIKE ' => '%'.$username.'%'
					)
				)
			);

			$totalCount = $this->find(
				'count'
			) * 3;

			$risk = 0;
			$factor = ClassRegistry::init('Session')->find('count');
			// no risk
			if ($totalCount === $badCount || $totalCount === 0 || $factor == 0) {
				return $risk;
			}

			$risk = (($badCount/$factor) / $totalCount)*100;

			switch($risk){
				case ($risk < .5):
					return 2;
					break;

				case ($risk < 1):
					return 3;
					break;

				case ($risk < 2):
					return 4;
					break;

				case ($risk < 5):
					return 5;
					break;

				case ($risk < 10):
					return 4;
					break;

				case ($risk < 20):
					return 2;
					break;

				case ($risk < 50):
					return 2;
					break;

				default:
					return 1;
					break;
			} // switch
		}

		/**
		 * Block an ip address.
		 *
		 * This method is called after a number of failed logins and will create
		 * an entry into the database blocking the ip address from accessing the
		 * site.
		 *
		 * case:
		 * - first time being blocked (1) with no risk (1) on a site that has security on low (5)
		 *   - 1 x 1 x 5 x 5 = 25 min blocked
		 *
		 * - first time being blocked (1) with no risk (1) on a site that has security on high (20)
		 *   - 1 x 1 x 20 x 5 = 100 min blocked
		 *
		 * - 3rd time being blocked (3) with low risk (2) on a site that has security on low (5)
		 *   - 3 x 2 x 5 x 5 = 150 min blocked
		 *
		 * - 3rd time being blocked (3) with high risk (5) on a site that has security on high (20)
		 *   - 3 x 5 x 20 x 5 = 1500 min blocked (25 hours)
		 *
		 * @param mixed $ipAddress the users ip address
		 * @param mixed $data the data that was submited for that request.
		 * @param integer $risk the risk determined for that login.
		 *
		 * @return bool true on succes. or exit
		 */
		public function blockIp($ipAddress = null, $data = null, $risk = 0){
			if (!$ipAddress) {
				exit;
			}

			$old = $this->find(
				'first',
				array(
					'conditions' => array(
						$this->alias . '.ip_address' => $ipAddress
					),
					'contain' => false
				)
			);
			
			if(!(is_string($data) || is_int($data))) {
				$data = serialize($data);
			}

			$save[$this->alias]['ip_address'] = $ipAddress;
			$save[$this->alias]['active'] = 1;
			$save[$this->alias]['description'] = $data;
			$save[$this->alias]['times_blocked'] = 1;
			$save[$this->alias]['risk'] = $risk;
			$save[$this->alias]['unlock_at'] = time();

			if (!empty($old)) {
				$save[$this->alias]['id'] = $old[$this->alias]['id'];
				$save[$this->alias]['times_blocked'] = $old[$this->alias]['times_blocked'] + 1;
				$save[$this->alias]['unlock_at'] = strtotime($old[$this->alias]['unlock_at']);
			}

			$time = $save[$this->alias]['times_blocked'] * 20;
			switch(Configure::read('Security.level')){
				case 'low':
					$time = $save[$this->alias]['times_blocked'] * 5;
					break;
				
				case 'medium':
					$time = $save[$this->alias]['times_blocked'] * 10;
					break;
			} // switch
			
			$save[$this->alias]['unlock_at'] = date('Y-m-d H:i:s', ($time * 5 * 60 * ($risk + 1)) + $save[$this->alias]['unlock_at']);

			if ($this->save($save)) {
				return true;
			}
		}
	}