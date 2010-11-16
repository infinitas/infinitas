<?php
	Class IpLocation extends Object{
		private $__emptyCountry = array('country' => false, 'country_code' => false);
		private $__emptyCity = array('country' => false, 'country_code' => false);
		
		public function __construct(){
			$this->countryDataFile = dirname(dirname(__FILE__)).DS.'libs'.DS.'geoip'.DS.'country.dat';
			$this->cityDataFile = dirname(dirname(__FILE__)).DS.'libs'.DS.'geoip'.DS.'city.dat';
		}

		private function __loadFile($type = 'country'){
			$type = strtolower((string)$type);
			App::import('Lib', 'GeoLocation.Geoip/inc.php');
					
			switch($type){
				case 'country':
					if(!is_file($this->countryDataFile)){
						trigger_error(sprintf(__('%s data file is missing', true), $type), E_USER_WARNING);
						return false;
					}
					return geoip_open($this->countryDataFile, GEOIP_STANDARD);
					break;
				
				case 'city':
					App::import('Lib', 'GeoLocation.Geoip/city.php');
					App::import('Lib', 'GeoLocation.Geoip/region_vars.php');
					if(!is_file($this->cityDataFile)){
						trigger_error(sprintf(__('%s data file is missing', true), $type), E_USER_WARNING);
						return false;
					}
					return geoip_open($this->cityDataFile, GEOIP_STANDARD);
					break;

				default:
					trigger_error(sprintf(__('%s is not a valid data file', true), $type), E_USER_WARNING);
					return false;
					break;
			}
		}

		private function __getIpAddress(){
			App::import('Component', 'RequestHandler');
			$RequestHandler = new RequestHandlerComponent();
			$ipAddress = $RequestHandler->getClientIP();
			unset($RequestHandler);

			return $ipAddress;
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
		public function getCountryData($ipAddress = null, $code = false){
			if (!$ipAddress){
				$ipAddress = $this->__getIpAddress();
			}
			
			$data = $this->__loadFile();
			if(!$data){
				return $this->__emptyCountry;
			}

			$return = array(
				'country' => geoip_country_name_by_addr($data, $ipAddress),
				'country_code' => geoip_country_code_by_addr($data, $ipAddress),
				'country_id' => geoip_country_id_by_addr($data, $ipAddress),
				'ip_address' => $ipAddress
			);
			
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
		public function getCityData($ipAddress = null, $fields = array()){
			if (!$ipAddress){
				$ipAddress = $this->__getIpAddress();
			}

			$data = $this->__loadFile('city');
			if(!$data){
				return false;
			}

			$city = (array)geoip_record_by_addr($data, $ipAddress);
			if(!empty($city)){
				$city['country'] = $city['country_name'];
				unset($city['country_name']);
				$city['ip_address'] = $ipAddress;
			}
			geoip_close($data);
			unset($data);

			return (array)$city;
		}
	}