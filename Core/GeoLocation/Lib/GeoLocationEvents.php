<?php
	final class GeoLocationEvents extends AppEvents{
		public function onRequireComponentsToLoad($event = null) {			
			return array(
				'GeoLocation.GeoLocation'
			);
		}

		/**
		 * Get the location, will try for city, but if not available will get the
		 * country.
		 *
		 * @param object $event the event object
		 * @param string $ipAddress the ip address
		 * @return array the details requested
		 */
		public function onGetLocation($event, $ipAddress = null){
			App::import('GeoIp', 'GeoIp.IpLocation');
			$IpLocation = new IpLocation();

			$return = $IpLocation->getCityData($ipAddress);
			if(!$return){
				$return = $IpLocation->getCountryData($ipAddress);
			}

			unset($IpLocation);
			return $return;
		}

		/**
		 * Get the country data
		 *
		 * @param object $event the event object
		 * @param string $ipAddress the ip address
		 * @return array the details requested
		 */
		public function onGetCountry($event, $ipAddress = null){
			App::import('GeoIp', 'GeoIp.IpLocation');
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
		public function onGetCity($event, $ipAddress = null){			
			App::import('GeoIp', 'GeoIp.IpLocation');
			$IpLocation = new IpLocation();

			$return = $IpLocation->getCityData($ipAddress);

			unset($IpLocation);
			return $return;
		}
	}