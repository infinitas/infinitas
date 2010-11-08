<?php
	Class IpLocation extends Object{
		private $__emptyCountry = array('country' => false, 'country_code' => false);
		
		public function __construct(){
			$this->countryDataFile = dirname(dirname(__FILE__)).DS.'libs'.DS.'geoip'.DS.'country.dat';
			$this->cityDataFile = dirname(dirname(__FILE__)).DS.'libs'.DS.'geoip'.DS.'city.dat';
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

			App::import('Lib', 'Libs.Geoip/inc.php');
			if (!is_file($this->countryDataFile)) {

				//pr($ipAddress);
				return $this->__emptyCountry;
			}

			$data = geoip_open($this->countryDataFile, GEOIP_STANDARD);

			$country = geoip_country_name_by_addr($data, $ipAddress);
			$country = empty($country) ? 'Unknown' : $country;

			if ($code) {
				$code = geoip_country_code_by_addr($data, $ipAddress);
				$code = empty($code) ? 'Unknown' : $code;
			}

			geoip_close($data);

			return array('country' => $country, 'country_code' => $code);
		}
	}