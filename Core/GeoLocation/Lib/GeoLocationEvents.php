<?php
App::uses('IpLocation', 'GeoLocation.Lib');
/**
 * @brief GeoLocation events class
 *
 * This class provides a means for plugins to get location data when available
 */
final class GeoLocationEvents extends AppEvents {
/**
 * @brief load location components
 *
 * @param Event $event
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'GeoLocation.GeoLocation'
		);
	}

/**
* Get the location, will try for city, but if not available will get the
* country.
*
* @param object $event the event object
 *
* @param string $ipAddress the ip address
 *
* @return array the details requested
*/
	public function onGetLocation($event, $ipAddress = null) {
		return $this->onGetCity($event, $ipAddress) || $this->onGetCountry($event, $ipAddress);
	}

/**
* Get the country data
*
* @param object $event the event object
* @param string $ipAddress the ip address
* @return array the details requested
*/
	public function onGetCountry($event, $ipAddress = null) {
		$IpLocation = new IpLocation();

		$return = $IpLocation->getCountryData($ipAddress);

		unset($IpLocation);
		return $return;
	}

/**
* Get the city data
*
* @param object $event the event object
* @param string $ipAddress the ip address
* @return array the details requested
*/
	public function onGetCity($event, $ipAddress = null) {
		$IpLocation = new IpLocation();

		$return = $IpLocation->getCityData($ipAddress);

		unset($IpLocation);
		return $return;
	}
}