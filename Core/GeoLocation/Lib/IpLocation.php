<?php
/**
 * IpLocation
 *
 * @package Infinitas.GeoLocation.Lib
 */

App::uses('GeoIP', 'GeoLocation.Lib/GeoIP');
App::uses('GeoIPCity', 'GeoLocation.Lib/GeoIP');

/**
 * IpLocation
 *
 * Class for getting the location based on the ip address.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.GeoLocation.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

Class IpLocation extends Object {
/**
 * Constructor
 *
 * Set the paths to the GeoIP data files
 *
 * @return void
 */
	public function __construct() {
		$this->countryDataFile = App::pluginPath('GeoLocation') . 'Lib' . DS . 'GeoIP' . DS . 'country.dat';
		$this->cityDataFile = App::pluginPath('GeoLocation') . 'Lib' . DS . 'GeoIP' . DS . 'city.dat';
	}

/**
 * load up the classes and database
 *
 * @param string $type the type of lookup being done
 *
 * @see IpLocation::_openDb()
 *
 * @return GeoIP
 *
 * @throws InvalidArgumentException
 */
	protected function _loadFile($type = 'country') {
		$type = strtolower((string)$type);

		new GeoIP();
		switch($type) {
			case 'country':
				if (!$this->hasCountryData()) {
					throw new InvalidArgumentException(sprintf(__d('geo_location', '%s data file is missing (tried: %s)'), $type, $this->countryDataFile));
					return false;
				}

				return $this->_openDb($this->countryDataFile);
				break;

			case 'city':
				new GeoIPCity();
				if (!$this->hasCityData()) {
					return false;
				}

				App::import('Lib', 'GeoLocation.Geoip/region_vars.php');

				return $this->_openDb($this->cityDataFile);
				break;
		}

		throw new InvalidArgumentException(sprintf(__d('geo_location', '%s is not a valid data file'), $type));
	}

/**
 * open the geoip database
 *
 * @param string $type the type of db being opened
 *
 * @return GeoIP
 */
	protected function _openDb($type) {
		return geoip_open($type, GEOIP_STANDARD);
	}

/**
 * get the client ip address if not passed in
 *
 * @see CakeRequest::clientIp()
 *
 * @return string
 */
	protected function _getIpAddress() {
		$CakeRequest = InfinitasRouter::getRequest();
		if (!$CakeRequest instanceof CakeRequest) {
			return false;
		}

		return $CakeRequest->clientIp();
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
 * @return array
 */
	public function getCountryData($ipAddress = null, $code = false) {
		if (!$ipAddress) {
			$ipAddress = $this->_getIpAddress();
		}

		$empty = array(
			'country' => null,
			'country_code' => null,
			'country_id' => null,
			'city' => null,
			'ip_address' => null
		);
		$data = $this->_loadFile();
		if (!$data) {
			return $empty;
		}

		$return = array_merge($empty, array(
			'country' => geoip_country_name_by_addr($data, $ipAddress),
			'country_code' => geoip_country_code_by_addr($data, $ipAddress),
			'country_id' => geoip_country_id_by_addr($data, $ipAddress),
			'ip_address' => $ipAddress
		));

		if (empty($return['country']) && $ipAddress == '127.0.0.1') {
			$return['country'] = $return['city'] = 'localhost';
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
 * @param array $fields not used
 *
 * @todo remove $fields variable
 *
 * @return array
 */
	public function getCityData($ipAddress = null, $fields = array()) {
		if (!$ipAddress) {
			$ipAddress = $this->_getIpAddress();
		}

		$data = $this->_loadFile('city');
		if (!$data) {
			return false;
		}

		$city = array_merge(
			array(
				'country_code3' => null,
				'region' => null,
				'postal_code' => null,
				'latitude' => null,
				'longitude' => null,
				'area_code' => null,
				'dma_code' => null,
				'metro_code' => null,
				'continent_code' => null
			),
			$this->getCountryData($ipAddress),
			(array)geoip_record_by_addr($data, $ipAddress)
		);

		if (!empty($city['country_name'])) {
			$city['country'] = $city['country_name'];
			unset($city['country_name']);
		}

		if (empty($city['country']) && $ipAddress == '127.0.0.1') {
			$city['country'] = 'localhost';
			$city['city'] = 'localhost';
		}

		geoip_close($data);
		unset($data);

		return $city;
	}

/**
 * check if the city data file is available
 *
 * @return boolean
 */
	public function hasCityData() {
		return is_file($this->cityDataFile);
	}

/**
 * check if the country data file is available
 *
 * @return boolean
 */
	public function hasCountryData() {
		return is_file($this->countryDataFile);
	}

}