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
			App::import('Lib', 'Libs.Geoip/inc.php');
			switch($type){
				case 'country':
					if(!is_file($this->countryDataFile)){
						trigger_error(sprintf(__('%s data file is missing', true), $type), E_USER_WARNING);
						return false;
					}
					return geoip_open($this->countryDataFile, GEOIP_STANDARD);
					break;
				
				case 'city':
					App::import('Lib', 'Libs.Geoip/city.php');
					App::import('Lib', 'Libs.Geoip/region_vars.php');
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
				return $this->__emptyCountry;
			}

			$data = $this->__loadFile();
			if(!$data){
				return $this->__emptyCountry;
			}

			$country = geoip_country_name_by_addr($data, $ipAddress);
			pr($country);
			exit;
			$country = empty($country) ? 'Unknown' : $country;

			if ($code) {
				$code = geoip_country_code_by_addr($data, $ipAddress);
				$code = empty($code) ? 'Unknown' : $code;
			}

			geoip_close($data);

			return array('country' => $country, 'country_code' => $code);
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
				return $this->__emptyCity;
			}

			$data = $this->__loadFile('city');
			if(!$data){
				return $this->__emptyCity;
			}

			$city = geoip_record_by_addr($data, $ipAddress);
			geoip_close($data);

			return (array)$city;
		}
	}