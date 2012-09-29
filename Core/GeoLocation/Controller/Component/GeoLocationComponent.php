<?php
App::uses('InfinitasComponent', 'Libs.Controller/Component');
App::uses('IpLocation', 'GeoLocation.Lib');

class GeoLocationComponent extends InfinitasComponent {
/**
 * default configuration
 */
	public $configs = array();

/**
 * @brief load up the users location data when available
 *
 * @param Controller $Controller
 */
	public function initialize(Controller $Controller) {
		parent::initialize($Controller);

		if(!$this->Controller->Session->read('GeoLocation')) {
			$data = $this->Controller->Event->trigger('GeoLocation.getLocation');
			$this->Controller->Session->write('GeoLocation', current($data['getLocation']));
		}
	}

/**
 * Find users country.
 *
 * Attempt to get the country the user is from.  returns unknown if its not
 * able to match something.
 */
	public function getCountryData($ipAddress = null, $code = false) {
		$IpLocation = new IpLocation();
		return $IpLocation->getCountryData($ipAddress, $code);
	}

/**
 * Get the city the user is in.
 */
	public function getCityData($ipAddress = null) {
		$IpLocation = new IpLocation();
		return $IpLocation->getCityData($ipAddress);
	}
}