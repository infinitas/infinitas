<?php
	/**
	 *
	 *
	 */
	class GeoLocationComponent extends Object{

		/**
		* components being used here
		*/
		public $components = array(
			'Session',
			'RequestHandler',
			'Event.Event',
		);

		/**
		* default configuration
		*/
		public $configs = array();

		private $__emptyCountry = array('country' => false, 'country_code' => false);

		private $__emptyCity = array(
			'latitude' => false, 'longitude' => false, 'region'   => false,
			'city'     => false, 'postcode'  => false, 'areaCode' => false
		);

		public function initialize(&$controller, $settings = array()) {
			$this->Controller = &$controller;
			$settings = array_merge(array(), (array)$settings);

			$this->countryDataFile = dirname(dirname(dirname(__FILE__))).DS.'libs'.DS.'geoip'.DS.'country.dat';
			$this->cityDataFile = dirname(dirname(dirname(__FILE__))).DS.'libs'.DS.'geoip'.DS.'city.dat';

			$this->__autoUserLocation();
		}

		private function __autoUserLocation(){
			if(!$this->Session->read('GeoLocation')){
				$data = $this->Event->trigger('geoip.getLocation');
				$this->Session->write('GeoLocation', current($data['getLocation']));
			}
		}

		/**
		 * Find users country.
		 *
		 * Attempt to get the country the user is from.  returns unknown if its not
		 * able to match something.
		 */
		public function getCountryData($ipAddress = null, $code = false){
			if (!$ipAddress){
				$ipAddress = $this->RequestHandler->getClientIP();
				if (!$ipAddress) {
					return $this->__emptyCountry;
				}
			}

			App::import('Lib', 'Libs.Geoip/inc.php');
			if (!is_file($this->countryDataFile)) {
				return $this->__emptyCountry;
			}

			$data = geoip_open($this->countryDataFile, GEOIP_STANDARD);

			$country = geoip_country_name_by_addr($data, $ipAddress);
			$country = empty($country) ? 'Unknown' : $country;

			if ($code) {
				$code = geoip_country_code_by_addr($data, $ip_address);
				$code = empty($code) ? 'Unknown' : $code;
			}

			geoip_close($data);

			return array('country' => $country, 'country_code' => $code);
		}

		/**
		 * Get the city the user is in.
		 */
		public function getCityData($ipAddress = null){
			if (!$ipAddress){
				$ipAddress = $this->RequestHandler->getClientIP();
				if (!$ipAddress) {
					return $this->__emptyCity;
				}
			}

			App::import('Lib', 'Libs.Geoip/inc.php');
			App::import('Lib', 'Libs.Geoip/city.php');
			App::import('Lib', 'Libs.Geoip/region_vars.php');

			if(!is_file($this->cityDataFile)){
				return $this->__emptyCity;
			}

			$gi = geoip_open($this->cityDataFile ,GEOIP_STANDARD);

			$data = geoip_record_by_addr($gi, $ipAddress);
			geoip_close($gi);

			pr($data);
			exit;

			return $this->cityData;
		}
	}