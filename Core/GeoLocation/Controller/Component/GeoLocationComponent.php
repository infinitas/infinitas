<?php
/**
 * GeoLocationComponent
 *
 * @package Infinitas.GeoLocation.Controller.Component
 */

App::uses('InfinitasComponent', 'Libs.Controller/Component');
App::uses('IpLocation', 'GeoLocation.Lib');

/**
 * GeoLocationComponent
 *
 * The events that can be triggered for the events class.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.GeoLocation.Controller.Component
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class GeoLocationComponent extends InfinitasComponent {
/**
 * default configuration
 */
	public $configs = array();

/**
 * Load up the users location data when available
 *
 * @param Controller $Controller
 */
	public function initialize(Controller $Controller) {
		parent::initialize($Controller);

		if (!$this->Controller->Session->read('GeoLocation')) {
			$data = $this->Controller->Event->trigger('GeoLocation.getLocation');
			$this->Controller->Session->write('GeoLocation', current($data['getLocation']));
		}
	}

/**
 * Find users country.
 *
 * Attempt to get the country the user is from.  returns unknown if its not
 * able to match something.
 *
 * @param string $ipAddress the ip address to look up
 * @param string $code
 *
 * @return array
 */
	public function getCountryData($ipAddress = null, $code = false) {
		$IpLocation = new IpLocation();
		return $IpLocation->getCountryData($ipAddress, $code);
	}

/**
 * Get the city the user is in.
 *
 * @param string $ipAddress the ip address to look up
 *
 * @return array
 */
	public function getCityData($ipAddress = null) {
		$IpLocation = new IpLocation();
		return $IpLocation->getCityData($ipAddress);
	}

}