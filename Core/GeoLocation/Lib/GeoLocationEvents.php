<?php
/**
 * GeoLocation events class
 *
 * @package Infinitas.GeoLocation.Lib
 */

App::uses('IpLocation', 'GeoLocation.Lib');

/**
 * GeoLocation events class
 *
 * This class provides a means for plugins to get location data when available
 * through the event system.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.GeoLocation.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class GeoLocationEvents extends AppEvents {
/**
 * load location components
 *
 * @param Event $Event
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'GeoLocation.GeoLocation'
		);
	}

/**
 * Get the location,
 *
 * Will try for city, but if not available will get the country.
 *
 * @param Event $Event the event object
 * @param string $ipAddress the ip address
 *
 * @return array
 */
	public function onGetLocation(Event $Event, $ipAddress = null) {
		$city = $this->onGetCity($Event, $ipAddress);
		if ($city) {
			return $city;
		}
		return $this->onGetCountry($Event, $ipAddress);
	}

/**
 * Get the country data
 *
 * @param Event $Event the event object
 * @param string $ipAddress the ip address
 *
 * @return array
 */
	public function onGetCountry(Event $Event, $ipAddress = null) {
		$IpLocation = new IpLocation();

		$return = $IpLocation->getCountryData($ipAddress);

		unset($IpLocation);
		return $return;
	}

/**
 * Get the city data
 *
 * @param Event $Event the event object
 * @param string $ipAddress the ip address
 *
 * @return array
 */
	public function onGetCity(Event $Event, $ipAddress = null) {
		$IpLocation = new IpLocation();

		$return = $IpLocation->getCityData($ipAddress);

		unset($IpLocation);
		return $return;
	}

}