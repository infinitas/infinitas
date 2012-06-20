<?php
	App::uses('GeoIP', 'GeoLocation.Lib/GeoIP');
	
	Class IpLocation extends Object {
		private $__emptyCountry = array('country' => false, 'country_code' => false);
		private $__emptyCity = array('country' => false, 'country_code' => false);
		
		public function __construct() {
			$this->countryDataFile = App::pluginPath('GeoLocation') . 'Lib' . DS . 'GeoIP' . DS . 'country.dat';
			$this->cityDataFile = App::pluginPath('GeoLocation') . 'Lib' . DS . 'GeoIP' . DS . 'city.dat';
		}

		private function __loadFile($type = 'country') {
			$type = strtolower((string)$type);

			new GeoIP();
			
			switch($type) {
				case 'country':
					if(!is_file($this->countryDataFile)) {
						throw new Exception(sprintf(__('%s data file is missing'), $type));
						return false;
					}
					
					return geoip_open($this->countryDataFile, GEOIP_STANDARD);
					break;
				
				case 'city':
					if(!is_file($this->cityDataFile)) {
						return false;
					}

					App::import('Lib', 'GeoLocation.Geoip/city.php');
					App::import('Lib', 'GeoLocation.Geoip/region_vars.php');
					
					return geoip_open($this->cityDataFile, GEOIP_STANDARD);
					break;

				default:
					throw new Exception(sprintf(__('%s is not a valid data file'), $type));
					break;
			}
		}

		private function __getIpAddress() {
			return CakeRequest::clientIp();
		}

		/**
		 * Find users country.
		 *
		 * Attempt to get the country the user is from.  returns unknown if its not
		 * able to match something.
		 *
		 * @param string $ipAddress the ip address to check against
		 * @param bool $code get the country code or not
		 *
		 * @return array the data requested
		 */
		public function getCountryData($ipAddress = null, $code = false) {
			if (!$ipAddress) {
				$ipAddress = $this->__getIpAddress();
			}
			
			$data = $this->__loadFile();
			if(!$data) {
				return $this->__emptyCountry;
			}

			$return = array(
				'country' => geoip_country_name_by_addr($data, $ipAddress),
				'country_code' => geoip_country_code_by_addr($data, $ipAddress),
				'country_id' => geoip_country_id_by_addr($data, $ipAddress),
				'city' => null,
				'ip_address' => $ipAddress
			);

			if(empty($return['country']) && $ipAddress == '127.0.0.1') {
				$return['country'] = 'localhost';
				$return['city'] = 'localhost';
			}
			
			geoip_close($data);
			unset($data);

			return $return;
		}

		/**
		 * Find users country.
		 *
		 * Attempt to get the country the user is from.  returns unknown if its not
		 * able to match something.
		 *
		 * @param string $ipAddress the ip address to check against
		 * @param bool $code get the country code or not
		 *
		 * @return array the data requested
		 */
		public function getCityData($ipAddress = null, $fields = array()) {
			if (!$ipAddress) {
				$ipAddress = $this->__getIpAddress();
			}

			$data = $this->__loadFile('city');
			if(!$data) {
				return false;
			}

			$city = (array)geoip_record_by_addr($data, $ipAddress);
			if(!empty($city)) {
				$city['country'] = $city['country_name'];
				unset($city['country_name']);
				$city['ip_address'] = $ipAddress;
			}

			if(empty($return['country']) && $ipAddress == '127.0.0.1') {
				$city['country'] = 'localhost';
				$city['city'] = 'localhost';
			}
			
			geoip_close($data);
			unset($data);

			return (array)$city;
		}
	}
