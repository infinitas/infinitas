<?php
/**
 * Time Zone lib
 *
 * @package Infinitas.GeoLocation.Lib
 */

App::uses('IpLocation', 'GeoLocation.Lib');

/**
 * Time Zone lib
 *
 * Get a time zone based on the two letter country code and region or ip address
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.GeoLocation.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class GeoLocationTimeZone {
/**
 * get the time zone based on the ip address
 *
 * @param string $ipAddress the ip address to lookup
 *
 * @return string
 */
	public static function fromIp($ipAddress) {
		$IpLocation = new IpLocation();
		$ipAddress = $IpLocation->getCityData($ipAddress);
		if (!$ipAddress) {
			return false;
		}

		return self::fromCountry($ipAddress['country_code'], $ipAddress['region']);
	}

/**
 * Get the time zone based on the country / region codes
 *
 * Copyright 2009 Maxmind LLC All Rights Reserved
 *
 * @param type $country the country to look in
 * @param type $region the region to look in
 *
 * @return boolean|string
 */
	public static function fromCountry($country, $region = null) {
		switch($country) {
			case 'US':
				switch($region) {
					case 'AL':
						return 'America/Chicago';
						break;
					case 'AK':
						return 'America/Anchorage';
						break;
					case 'AZ':
						return 'America/Phoenix';
						break;
					case 'AR':
						return 'America/Chicago';
						break;
					case 'CA':
						return 'America/Los_Angeles';
						break;
					case 'CO':
						return 'America/Denver';
						break;
					case 'CT':
						return 'America/New_York';
						break;
					case 'DE':
						return 'America/New_York';
						break;
					case 'DC':
						return 'America/New_York';
						break;
					case 'FL':
						return 'America/New_York';
						break;
					case 'GA':
						return 'America/New_York';
						break;
					case 'HI':
						return 'Pacific/Honolulu';
						break;
					case 'ID':
						return 'America/Denver';
						break;
					case 'IL':
						return 'America/Chicago';
						break;
					case 'IN':
						return 'America/Indianapolis';
						break;
					case 'IA':
						return 'America/Chicago';
						break;
					case 'KS':
						return 'America/Chicago';
						break;
					case 'KY':
						return 'America/New_York';
						break;
					case 'LA':
						return 'America/Chicago';
						break;
					case 'ME':
						return 'America/New_York';
						break;
					case 'MD':
						return 'America/New_York';
						break;
					case 'MA':
						return 'America/New_York';
						break;
					case 'MI':
						return 'America/New_York';
						break;
					case 'MN':
						return 'America/Chicago';
						break;
					case 'MS':
						return 'America/Chicago';
						break;
					case 'MO':
						return 'America/Chicago';
						break;
					case 'MT':
						return 'America/Denver';
						break;
					case 'NE':
						return 'America/Chicago';
						break;
					case 'NV':
						return 'America/Los_Angeles';
						break;
					case 'NH':
						return 'America/New_York';
						break;
					case 'NJ':
						return 'America/New_York';
						break;
					case 'NM':
						return 'America/Denver';
						break;
					case 'NY':
						return 'America/New_York';
						break;
					case 'NC':
						return 'America/New_York';
						break;
					case 'ND':
						return 'America/Chicago';
						break;
					case 'OH':
						return 'America/New_York';
						break;
					case 'OK':
						return 'America/Chicago';
						break;
					case 'OR':
						return 'America/Los_Angeles';
						break;
					case 'PA':
						return 'America/New_York';
						break;
					case 'RI':
						return 'America/New_York';
						break;
					case 'SC':
						return 'America/New_York';
						break;
					case 'SD':
						return 'America/Chicago';
						break;
					case 'TN':
						return 'America/Chicago';
						break;
					case 'TX':
						return 'America/Chicago';
						break;
					case 'UT':
						return 'America/Denver';
						break;
					case 'VT':
						return 'America/New_York';
						break;
					case 'VA':
						return 'America/New_York';
						break;
					case 'WA':
						return 'America/Los_Angeles';
						break;
					case 'WV':
						return 'America/New_York';
						break;
					case 'WI':
						return 'America/Chicago';
						break;
					case 'WY':
						return 'America/Denver';
						break;
				}
				break;
			case 'CA':
				switch($region) {
					case 'AB':
						return 'America/Edmonton';
						break;
					case 'BC':
						return 'America/Vancouver';
						break;
					case 'MB':
						return 'America/Winnipeg';
						break;
					case 'NB':
						return 'America/Moncton';
						break;
					case 'NL':
						return 'America/St_Johns';
						break;
					case 'NT':
						return 'America/Yellowknife';
						break;
					case 'NS':
						return 'America/Halifax';
						break;
					case 'NU':
						return 'America/Iqaluit';
						break;
					case 'ON':
						return 'America/Montreal';
						break;
					case 'PE':
						return 'America/Halifax';
						break;
					case 'QC':
						return 'America/Moncton';
						break;
					case 'SK':
						return 'America/Regina';
						break;
					case 'YT':
						return 'America/Whitehorse';
						break;
				}
				break;
			case 'AU':
				switch($region) {
					case '01':
						return 'Australia/Sydney';
						break;
					case '02':
						return 'Australia/Sydney';
						break;
					case '03':
						return 'Australia/Darwin';
						break;
					case '04':
						return 'Australia/Brisbane';
						break;
					case '05':
						return 'Australia/Adelaide';
						break;
					case '06':
						return 'Australia/Hobart';
						break;
					case '07':
						return 'Australia/Melbourne';
						break;
					case '08':
						return 'Australia/Perth';
						break;
				}
				break;
			case 'AS':
				return 'Pacific/Pago_Pago';
				break;
			case 'CI':
				return 'Africa/Abidjan';
				break;
			case 'GH':
				return 'Africa/Accra';
				break;
			case 'DZ':
				return 'Africa/Algiers';
				break;
			case 'ER':
				return 'Africa/Asmara';
				break;
			case 'ML':
				return 'Africa/Bamako';
				break;
			case 'CF':
				return 'Africa/Bangui';
				break;
			case 'GM':
				return 'Africa/Banjul';
				break;
			case 'GW':
				return 'Africa/Bissau';
				break;
			case 'CG':
				return 'Africa/Brazzaville';
				break;
			case 'BI':
				return 'Africa/Bujumbura';
				break;
			case 'EG':
				return 'Africa/Cairo';
				break;
			case 'MA':
				return 'Africa/Casablanca';
				break;
			case 'GN':
				return 'Africa/Conakry';
				break;
			case 'SN':
				return 'Africa/Dakar';
				break;
			case 'DJ':
				return 'Africa/Djibouti';
				break;
			case 'SL':
				return 'Africa/Freetown';
				break;
			case 'BW':
				return 'Africa/Gaborone';
				break;
			case 'ZW':
				return 'Africa/Harare';
				break;
			case 'ZA':
				return 'Africa/Johannesburg';
				break;
			case 'UG':
				return 'Africa/Kampala';
				break;
			case 'UM':
				return 'Pacific/Wake';
				break;
			case 'SD':
				return 'Africa/Khartoum';
				break;
			case 'RW':
				return 'Africa/Kigali';
				break;
			case 'NG':
				return 'Africa/Lagos';
				break;
			case 'GA':
				return 'Africa/Libreville';
				break;
			case 'TG':
				return 'Africa/Lome';
				break;
			case 'AO':
				return 'Africa/Luanda';
				break;
			case 'AQ':
				return 'Antarctica/South_Pole';
				break;
			case 'ZM':
				return 'Africa/Lusaka';
				break;
			case 'GQ':
				return 'Africa/Malabo';
				break;
			case 'MZ':
				return 'Africa/Maputo';
				break;
			case 'LS':
				return 'Africa/Maseru';
				break;
			case 'SZ':
				return 'Africa/Mbabane';
				break;
			case 'SO':
				return 'Africa/Mogadishu';
				break;
			case 'LR':
				return 'Africa/Monrovia';
				break;
			case 'KE':
				return 'Africa/Nairobi';
				break;
			case 'TD':
				return 'Africa/Ndjamena';
				break;
			case 'NE':
				return 'Africa/Niamey';
				break;
			case 'MR':
				return 'Africa/Nouakchott';
				break;
			case 'BF':
				return 'Africa/Ouagadougou';
				break;
			case 'ST':
				return 'Africa/Sao_Tome';
				break;
			case 'LY':
				return 'Africa/Tripoli';
				break;
			case 'TN':
				return 'Africa/Tunis';
				break;
			case 'AI':
				return 'America/Anguilla';
				break;
			case 'AG':
				return 'America/Antigua';
				break;
			case 'AW':
				return 'America/Aruba';
				break;
			case 'BB':
				return 'America/Barbados';
				break;
			case 'BZ':
				return 'America/Belize';
				break;
			case 'CO':
				return 'America/Bogota';
				break;
			case 'VE':
				return 'America/Caracas';
				break;
			case 'KY':
				return 'America/Cayman';
				break;
			case 'CR':
				return 'America/Costa_Rica';
				break;
			case 'DM':
				return 'America/Dominica';
				break;
			case 'SV':
				return 'America/El_Salvador';
				break;
			case 'GD':
				return 'America/Grenada';
				break;
			case 'FR':
				return 'Europe/Paris';
				break;
			case 'GP':
				return 'America/Guadeloupe';
				break;
			case 'GT':
				return 'America/Guatemala';
				break;
			case 'GY':
				return 'America/Guyana';
				break;
			case 'CU':
				return 'America/Havana';
				break;
			case 'JM':
				return 'America/Jamaica';
				break;
			case 'BO':
				return 'America/La_Paz';
				break;
			case 'PE':
				return 'America/Lima';
				break;
			case 'NI':
				return 'America/Managua';
				break;
			case 'MQ':
				return 'America/Martinique';
				break;
			case 'UY':
				return 'America/Montevideo';
				break;
			case 'MS':
				return 'America/Montserrat';
				break;
			case 'BS':
				return 'America/Nassau';
				break;
			case 'PA':
				return 'America/Panama';
				break;
			case 'SR':
				return 'America/Paramaribo';
				break;
			case 'PR':
				return 'America/Puerto_Rico';
				break;
			case 'KN':
				return 'America/St_Kitts';
				break;
			case 'LC':
				return 'America/St_Lucia';
				break;
			case 'VC':
				return 'America/St_Vincent';
				break;
			case 'HN':
				return 'America/Tegucigalpa';
				break;
			case 'YE':
				return 'Asia/Aden';
				break;
			case 'JO':
				return 'Asia/Amman';
				break;
			case 'TM':
				return 'Asia/Ashgabat';
				break;
			case 'IQ':
				return 'Asia/Baghdad';
				break;
			case 'BH':
				return 'Asia/Bahrain';
				break;
			case 'AZ':
				return 'Asia/Baku';
				break;
			case 'TH':
				return 'Asia/Bangkok';
				break;
			case 'LB':
				return 'Asia/Beirut';
				break;
			case 'KG':
				return 'Asia/Bishkek';
				break;
			case 'BN':
				return 'Asia/Brunei';
				break;
			case 'IN':
				return 'Asia/Kolkata';
				break;
			case 'MN':
				switch($region) {
					case '06':
						return 'Asia/Choibalsan';
						break;
					case '11':
						return 'Asia/Ulaanbaatar';
						break;
					case '17':
						return 'Asia/Choibalsan';
						break;
					case '19':
						return 'Asia/Hovd';
						break;
					case '20':
						return 'Asia/Ulaanbaatar';
						break;
					case '21':
						return 'Asia/Ulaanbaatar';
						break;
					case '25':
						return 'Asia/Ulaanbaatar';
						break;
				}
				break;
			case 'MO':
				return 'Asia/Macau';
				break;
			case 'LK':
				return 'Asia/Colombo';
				break;
			case 'BD':
				return 'Asia/Dhaka';
				break;
			case 'AE':
				return 'Asia/Dubai';
				break;
			case 'TJ':
				return 'Asia/Dushanbe';
				break;
			case 'HK':
				return 'Asia/Hong_Kong';
				break;
			case 'TR':
				return 'Europe/Istanbul';
				break;
			case 'IL':
				return 'Asia/Jerusalem';
				break;
			case 'AF':
				return 'Asia/Kabul';
				break;
			case 'PK':
				return 'Asia/Karachi';
				break;
			case 'NP':
				return 'Asia/Kathmandu';
				break;
			case 'KW':
				return 'Asia/Kuwait';
				break;
			case 'MO':
				return 'Asia/Macao';
				break;
			case 'PH':
				return 'Asia/Manila';
				break;
			case 'OM':
				return 'Asia/Muscat';
				break;
			case 'CY':
				return 'Asia/Nicosia';
				break;
			case 'KP':
				return 'Asia/Pyongyang';
				break;
			case 'QA':
				return 'Asia/Qatar';
				break;
			case 'MM':
				return 'Asia/Rangoon';
				break;
			case 'SA':
				return 'Asia/Riyadh';
				break;
			case 'KR':
				return 'Asia/Seoul';
				break;
			case 'SG':
				return 'Asia/Singapore';
				break;
			case 'TW':
				return 'Asia/Taipei';
				break;
			case 'GE':
				return 'Asia/Tbilisi';
				break;
			case 'BT':
				return 'Asia/Thimphu';
				break;
			case 'BV':
				return 'Antarctica/Syowa';
				break;
			case 'JP':
				return 'Asia/Tokyo';
				break;
			case 'LA':
				return 'Asia/Vientiane';
				break;
			case 'AM':
				return 'Asia/Yerevan';
				break;
			case 'BM':
				return 'Atlantic/Bermuda';
				break;
			case 'CV':
				return 'Atlantic/Cape_Verde';
				break;
			case 'FO':
				return 'Atlantic/Faroe';
				break;
			case 'FM':
				return 'Pacific/Pohnpei';
				break;
			case 'IS':
				return 'Atlantic/Reykjavik';
				break;
			case 'GS':
				return 'Atlantic/South_Georgia';
				break;
			case 'SH':
				return 'Atlantic/St_Helena';
				break;
			case 'CL':
				return 'Chile/Santiago';
				break;
			case 'NL':
				return 'Europe/Amsterdam';
				break;
			case 'AD':
				return 'Europe/Andorra';
				break;
			case 'GR':
				return 'Europe/Athens';
				break;
			case 'YU':
				return 'Europe/Belgrade';
				break;
			case 'DE':
				return 'Europe/Berlin';
				break;
			case 'SK':
				return 'Europe/Bratislava';
				break;
			case 'BE':
				return 'Europe/Brussels';
				break;
			case 'RO':
				return 'Europe/Bucharest';
				break;
			case 'HU':
				return 'Europe/Budapest';
				break;
			case 'DK':
				return 'Europe/Copenhagen';
				break;
			case 'IE':
				return 'Europe/Dublin';
				break;
			case 'GI':
				return 'Europe/Gibraltar';
				break;
			case 'FI':
				return 'Europe/Helsinki';
				break;
			case 'SI':
				return 'Europe/Ljubljana';
				break;
			case 'GB':
				return 'Europe/London';
				break;
			case 'LU':
				return 'Europe/Luxembourg';
				break;
			case 'MT':
				return 'Europe/Malta';
				break;
			case 'BY':
				return 'Europe/Minsk';
				break;
			case 'MC':
				return 'Europe/Monaco';
				break;
			case 'NO':
				return 'Europe/Oslo';
				break;
			case 'CZ':
				return 'Europe/Prague';
				break;
			case 'LV':
				return 'Europe/Riga';
				break;
			case 'IT':
				return 'Europe/Rome';
				break;
			case 'SM':
				return 'Europe/San_Marino';
				break;
			case 'BA':
				return 'Europe/Sarajevo';
				break;
			case 'MK':
				return 'Europe/Skopje';
				break;
			case 'BG':
				return 'Europe/Sofia';
				break;
			case 'SE':
				return 'Europe/Stockholm';
				break;
			case 'EE':
				return 'Europe/Tallinn';
				break;
			case 'AL':
				return 'Europe/Tirane';
				break;
			case 'LI':
				return 'Europe/Vaduz';
				break;
			case 'VA':
				return 'Europe/Vatican';
				break;
			case 'AT':
				return 'Europe/Vienna';
				break;
			case 'LT':
				return 'Europe/Vilnius';
				break;
			case 'PL':
				return 'Europe/Warsaw';
				break;
			case 'HR':
				return 'Europe/Zagreb';
				break;
			case 'IR':
				return 'Asia/Tehran';
				break;
			case 'MG':
				return 'Indian/Antananarivo';
				break;
			case 'CX':
				return 'Indian/Christmas';
				break;
			case 'CC':
				return 'Indian/Cocos';
				break;
			case 'KM':
				return 'Indian/Comoro';
				break;
			case 'MV':
				return 'Indian/Maldives';
				break;
			case 'MU':
				return 'Indian/Mauritius';
				break;
			case 'YT':
				return 'Indian/Mayotte';
				break;
			case 'RE':
				return 'Indian/Reunion';
				break;
			case 'FJ':
				return 'Pacific/Fiji';
				break;
			case 'TV':
				return 'Pacific/Funafuti';
				break;
			case 'GU':
				return 'Pacific/Guam';
				break;
			case 'NR':
				return 'Pacific/Nauru';
				break;
			case 'NU':
				return 'Pacific/Niue';
				break;
			case 'NF':
				return 'Pacific/Norfolk';
				break;
			case 'PW':
				return 'Pacific/Palau';
				break;
			case 'PN':
				return 'Pacific/Pitcairn';
				break;
			case 'CK':
				return 'Pacific/Rarotonga';
				break;
			case 'WS':
				return 'Pacific/Apia';
				break;
			case 'KI':
				return 'Pacific/Tarawa';
				break;
			case 'TO':
				return 'Pacific/Tongatapu';
				break;
			case 'WF':
				return 'Pacific/Wallis';
				break;
			case 'TZ':
				return 'Africa/Dar_es_Salaam';
				break;
			case 'VN':
				return 'Asia/Ho_Chi_Minh';
				break;
			case 'KH':
				return 'Asia/Phnom_Penh';
				break;
			case 'CM':
				return 'Africa/Douala';
				break;
			case 'DO':
				return 'America/Santo_Domingo';
				break;
			case 'TL':
				return 'Asia/Jakarta';
				break;
			case 'ET':
				return 'Africa/Addis_Ababa';
				break;
			case 'FX':
				return 'Europe/Paris';
				break;
			case 'HT':
				return 'America/Port-au-Prince';
				break;
			case 'CH':
				return 'Europe/Zurich';
				break;
			case 'AN':
				return 'America/Curacao';
				break;
			case 'BJ':
				return 'Africa/Porto-Novo';
				break;
			case 'EH':
				return 'Africa/El_Aaiun';
				break;
			case 'FK':
				return 'Atlantic/Stanley';
				break;
			case 'GF':
				return 'America/Cayenne';
				break;
			case 'IO':
				return 'Indian/Chagos';
				break;
			case 'MD':
				return 'Europe/Chisinau';
				break;
			case 'MP':
				return 'Pacific/Saipan';
				break;
			case 'MW':
				return 'Africa/Blantyre';
				break;
			case 'NA':
				return 'Africa/Windhoek';
				break;
			case 'NC':
				return 'Pacific/Noumea';
				break;
			case 'PG':
				return 'Pacific/Port_Moresby';
				break;
			case 'PM':
				return 'America/Miquelon';
				break;
			case 'PS':
				return 'Asia/Gaza';
				break;
			case 'PY':
				return 'America/Asuncion';
				break;
			case 'SB':
				return 'Pacific/Guadalcanal';
				break;
			case 'SC':
				return 'Indian/Mahe';
				break;
			case 'SJ':
				return 'Arctic/Longyearbyen';
				break;
			case 'SY':
				return 'Asia/Damascus';
				break;
			case 'TC':
				return 'America/Grand_Turk';
				break;
			case 'TF':
				return 'Indian/Kerguelen';
				break;
			case 'TK':
				return 'Pacific/Fakaofo';
				break;
			case 'TT':
				return 'America/Port_of_Spain';
				break;
			case 'VG':
				return 'America/Tortola';
				break;
			case 'VI':
				return 'America/St_Thomas';
				break;
			case 'VU':
				return 'Pacific/Efate';
				break;
			case 'RS':
				return 'Europe/Belgrade';
				break;
			case 'ME':
				return 'Europe/Podgorica';
				break;
			case 'AX':
				return 'Europe/Mariehamn';
				break;
			case 'GG':
				return 'Europe/Guernsey';
				break;
			case 'IM':
				return 'Europe/Isle_of_Man';
				break;
			case 'JE':
				return 'Europe/Jersey';
				break;
			case 'BL':
				return 'America/St_Barthelemy';
				break;
			case 'MF':
				return 'America/Marigot';
				break;
			case 'AR':
				switch($region) {
					case '01':
						return 'America/Argentina/Buenos_Aires';
						break;
					case '02':
						return 'America/Argentina/Catamarca';
						break;
					case '03':
						return 'America/Argentina/Tucuman';
						break;
					case '04':
						return 'America/Argentina/Rio_Gallegos';
						break;
					case '05':
						return 'America/Argentina/Cordoba';
						break;
					case '06':
						return 'America/Argentina/Tucuman';
						break;
					case '07':
						return 'America/Argentina/Buenos_Aires';
						break;
					case '08':
						return 'America/Argentina/Buenos_Aires';
						break;
					case '09':
						return 'America/Argentina/Tucuman';
						break;
					case '10':
						return 'America/Argentina/Jujuy';
						break;
					case '11':
						return 'America/Argentina/San_Luis';
						break;
					case '12':
						return 'America/Argentina/La_Rioja';
						break;
					case '13':
						return 'America/Argentina/Mendoza';
						break;
					case '14':
						return 'America/Argentina/Buenos_Aires';
						break;
					case '15':
						return 'America/Argentina/San_Luis';
						break;
					case '16':
						return 'America/Argentina/Mendoza';
						break;
					case '17':
						return 'America/Argentina/Salta';
						break;
					case '18':
						return 'America/Argentina/San_Juan';
						break;
					case '19':
						return 'America/Argentina/San_Luis';
						break;
					case '20':
						return 'America/Argentina/Rio_Gallegos';
						break;
					case '21':
						return 'America/Argentina/Buenos_Aires';
						break;
					case '22':
						return 'America/Argentina/Tucuman';
						break;
					case '23':
						return 'America/Argentina/Ushuaia';
						break;
					case '24':
						return 'America/Argentina/Tucuman';
						break;
				}
				break;
			case 'BR':
				switch($region) {
					case '01':
						return 'America/Rio_Branco';
						break;
					case '02':
						return 'America/Maceio';
						break;
					case '03':
						return 'America/Belem';
						break;
					case '04':
						return 'America/Manaus';
						break;
					case '05':
						return 'America/Bahia';
						break;
					case '06':
						return 'America/Fortaleza';
						break;
					case '07':
						return 'America/Sao_Paulo';
						break;
					case '08':
						return 'America/Sao_Paulo';
						break;
					case '11':
						return 'America/Campo_Grande';
						break;
					case '13':
						return 'America/Belem';
						break;
					case '14':
						return 'America/Cuiaba';
						break;
					case '15':
						return 'America/Sao_Paulo';
						break;
					case '16':
						return 'America/Belem';
						break;
					case '17':
						return 'America/Recife';
						break;
					case '18':
						return 'America/Sao_Paulo';
						break;
					case '20':
						return 'America/Fortaleza';
						break;
					case '21':
						return 'America/Sao_Paulo';
						break;
					case '22':
						return 'America/Recife';
						break;
					case '23':
						return 'America/Sao_Paulo';
						break;
					case '24':
						return 'America/Porto_Velho';
						break;
					case '25':
						return 'America/Boa_Vista';
						break;
					case '26':
						return 'America/Sao_Paulo';
						break;
					case '27':
						return 'America/Sao_Paulo';
						break;
					case '28':
						return 'America/Maceio';
						break;
					case '29':
						return 'America/Campo_Grande';
						break;
					case '30':
						return 'America/Recife';
						break;
					case '31':
						return 'America/Araguaina';
						break;
				}
				break;
			case 'CD':
				switch($region) {
					case '01':
						return 'Africa/Kinshasa';
						break;
					case '02':
						return 'Africa/Kinshasa';
						break;
					case '03':
						return 'Africa/Kinshasa';
						break;
					case '04':
						return 'Africa/Lubumbashi';
						break;
					case '05':
						return 'Africa/Lubumbashi';
						break;
					case '06':
						return 'Africa/Kinshasa';
						break;
					case '07':
						return 'Africa/Lubumbashi';
						break;
					case '08':
						return 'Africa/Kinshasa';
						break;
					case '09':
						return 'Africa/Lubumbashi';
						break;
					case '10':
						return 'Africa/Lubumbashi';
						break;
					case '11':
						return 'Africa/Lubumbashi';
						break;
					case '12':
						return 'Africa/Lubumbashi';
						break;
				}
				break;
			case 'CN':
				switch($region) {
					case '01':
						return 'Asia/Shanghai';
						break;
					case '02':
						return 'Asia/Shanghai';
						break;
					case '03':
						return 'Asia/Shanghai';
						break;
					case '04':
						return 'Asia/Shanghai';
						break;
					case '05':
						return 'Asia/Harbin';
						break;
					case '06':
						return 'Asia/Chongqing';
						break;
					case '07':
						return 'Asia/Shanghai';
						break;
					case '08':
						return 'Asia/Harbin';
						break;
					case '09':
						return 'Asia/Shanghai';
						break;
					case '10':
						return 'Asia/Shanghai';
						break;
					case '11':
						return 'Asia/Chongqing';
						break;
					case '12':
						return 'Asia/Shanghai';
						break;
					case '13':
						return 'Asia/Urumqi';
						break;
					case '14':
						return 'Asia/Kashgar';
						break;
					case '15':
						return 'Asia/Chongqing';
						break;
					case '16':
						return 'Asia/Chongqing';
						break;
					case '18':
						return 'Asia/Chongqing';
						break;
					case '19':
						return 'Asia/Harbin';
						break;
					case '20':
						return 'Asia/Harbin';
						break;
					case '21':
						return 'Asia/Chongqing';
						break;
					case '22':
						return 'Asia/Harbin';
						break;
					case '23':
						return 'Asia/Shanghai';
						break;
					case '24':
						return 'Asia/Chongqing';
						break;
					case '25':
						return 'Asia/Shanghai';
						break;
					case '26':
						return 'Asia/Chongqing';
						break;
					case '28':
						return 'Asia/Shanghai';
						break;
					case '29':
						return 'Asia/Chongqing';
						break;
					case '30':
						return 'Asia/Chongqing';
						break;
					case '31':
						return 'Asia/Chongqing';
						break;
					case '32':
						return 'Asia/Chongqing';
						break;
					case '33':
						return 'Asia/Chongqing';
						break;
				}
				break;
			case 'EC':
				switch($region) {
					case '01':
						return 'Pacific/Galapagos';
						break;
					case '02':
						return 'America/Guayaquil';
						break;
					case '03':
						return 'America/Guayaquil';
						break;
					case '04':
						return 'America/Guayaquil';
						break;
					case '05':
						return 'America/Guayaquil';
						break;
					case '06':
						return 'America/Guayaquil';
						break;
					case '07':
						return 'America/Guayaquil';
						break;
					case '08':
						return 'America/Guayaquil';
						break;
					case '09':
						return 'America/Guayaquil';
						break;
					case '10':
						return 'America/Guayaquil';
						break;
					case '11':
						return 'America/Guayaquil';
						break;
					case '12':
						return 'America/Guayaquil';
						break;
					case '13':
						return 'America/Guayaquil';
						break;
					case '14':
						return 'America/Guayaquil';
						break;
					case '15':
						return 'America/Guayaquil';
						break;
					case '17':
						return 'America/Guayaquil';
						break;
					case '18':
						return 'America/Guayaquil';
						break;
					case '19':
						return 'America/Guayaquil';
						break;
					case '20':
						return 'America/Guayaquil';
						break;
					case '22':
						return 'America/Guayaquil';
						break;
					case '24':
						return 'America/Guayaquil';
						break;
				}
				break;
			case 'ES':
				switch($region) {
					case '07':
						return 'Europe/Madrid';
						break;
					case '27':
						return 'Europe/Madrid';
						break;
					case '29':
						return 'Europe/Madrid';
						break;
					case '31':
						return 'Europe/Madrid';
						break;
					case '32':
						return 'Europe/Madrid';
						break;
					case '34':
						return 'Europe/Madrid';
						break;
					case '39':
						return 'Europe/Madrid';
						break;
					case '51':
						return 'Africa/Ceuta';
						break;
					case '52':
						return 'Europe/Madrid';
						break;
					case '53':
						return 'Atlantic/Canary';
						break;
					case '54':
						return 'Europe/Madrid';
						break;
					case '55':
						return 'Europe/Madrid';
						break;
					case '56':
						return 'Europe/Madrid';
						break;
					case '57':
						return 'Europe/Madrid';
						break;
					case '58':
						return 'Europe/Madrid';
						break;
					case '59':
						return 'Europe/Madrid';
						break;
					case '60':
						return 'Europe/Madrid';
						break;
				}
				break;
			case 'GL':
				switch($region) {
					case '01':
						return 'America/Thule';
						break;
					case '02':
						return 'America/Godthab';
						break;
					case '03':
						return 'America/Godthab';
						break;
				}
				break;
			case 'ID':
				switch($region) {
					case '01':
						return 'Asia/Pontianak';
						break;
					case '02':
						return 'Asia/Makassar';
						break;
					case '03':
						return 'Asia/Jakarta';
						break;
					case '04':
						return 'Asia/Jakarta';
						break;
					case '05':
						return 'Asia/Jakarta';
						break;
					case '06':
						return 'Asia/Jakarta';
						break;
					case '07':
						return 'Asia/Jakarta';
						break;
					case '08':
						return 'Asia/Jakarta';
						break;
					case '09':
						return 'Asia/Jayapura';
						break;
					case '10':
						return 'Asia/Jakarta';
						break;
					case '11':
						return 'Asia/Pontianak';
						break;
					case '12':
						return 'Asia/Makassar';
						break;
					case '13':
						return 'Asia/Pontianak';
						break;
					case '14':
						return 'Asia/Makassar';
						break;
					case '15':
						return 'Asia/Jakarta';
						break;
					case '16':
						return 'Asia/Makassar';
						break;
					case '17':
						return 'Asia/Makassar';
						break;
					case '18':
						return 'Asia/Makassar';
						break;
					case '19':
						return 'Asia/Pontianak';
						break;
					case '20':
						return 'Asia/Makassar';
						break;
					case '21':
						return 'Asia/Makassar';
						break;
					case '22':
						return 'Asia/Makassar';
						break;
					case '23':
						return 'Asia/Makassar';
						break;
					case '24':
						return 'Asia/Jakarta';
						break;
					case '25':
						return 'Asia/Pontianak';
						break;
					case '26':
						return 'Asia/Pontianak';
						break;
					case '28':
						return 'Asia/Makassar';
						break;
					case '29':
						return 'Asia/Makassar';
						break;
					case '30':
						return 'Asia/Jakarta';
						break;
					case '31':
						return 'Asia/Makassar';
						break;
					case '32':
						return 'Asia/Jakarta';
						break;
					case '33':
						return 'Asia/Jakarta';
						break;
					case '34':
						return 'Asia/Makassar';
						break;
					case '35':
						return 'Asia/Pontianak';
						break;
					case '36':
						return 'Asia/Jayapura';
						break;
					case '37':
						return 'Asia/Pontianak';
						break;
					case '38':
						return 'Asia/Makassar';
						break;
					case '39':
						return 'Asia/Jayapura';
						break;
					case '40':
						return 'Asia/Pontianak';
						break;
					case '41':
						return 'Asia/Makassar';
						break;
				}
				break;
			case 'KZ':
				switch($region) {
					case '01':
						return 'Asia/Almaty';
						break;
					case '02':
						return 'Asia/Almaty';
						break;
					case '03':
						return 'Asia/Qyzylorda';
						break;
					case '04':
						return 'Asia/Aqtobe';
						break;
					case '05':
						return 'Asia/Qyzylorda';
						break;
					case '06':
						return 'Asia/Aqtau';
						break;
					case '07':
						return 'Asia/Oral';
						break;
					case '08':
						return 'Asia/Qyzylorda';
						break;
					case '09':
						return 'Asia/Aqtau';
						break;
					case '10':
						return 'Asia/Qyzylorda';
						break;
					case '11':
						return 'Asia/Almaty';
						break;
					case '12':
						return 'Asia/Almaty';
						break;
					case '13':
						return 'Asia/Aqtobe';
						break;
					case '14':
						return 'Asia/Qyzylorda';
						break;
					case '15':
						return 'Asia/Almaty';
						break;
					case '16':
						return 'Asia/Aqtobe';
						break;
					case '17':
						return 'Asia/Almaty';
						break;
				}
				break;
			case 'MX':
				switch($region) {
					case '01':
						return 'America/Bahia_Banderas';
						break;
					case '02':
						return 'America/Tijuana';
						break;
					case '03':
						return 'America/Mazatlan';
						break;
					case '04':
						return 'America/Merida';
						break;
					case '05':
						return 'America/Merida';
						break;
					case '06':
						return 'America/Chihuahua';
						break;
					case '07':
						return 'America/Monterrey';
						break;
					case '08':
						return 'America/Bahia_Banderas';
						break;
					case '09':
						return 'America/Mexico_City';
						break;
					case '10':
						return 'America/Mazatlan';
						break;
					case '11':
						return 'America/Mexico_City';
						break;
					case '12':
						return 'America/Mexico_City';
						break;
					case '13':
						return 'America/Mexico_City';
						break;
					case '14':
						return 'America/Bahia_Banderas';
						break;
					case '15':
						return 'America/Ojinaga';
						break;
					case '16':
						return 'America/Mexico_City';
						break;
					case '17':
						return 'America/Mexico_City';
						break;
					case '18':
						return 'America/Bahia_Banderas';
						break;
					case '19':
						return 'America/Monterrey';
						break;
					case '20':
						return 'America/Mexico_City';
						break;
					case '21':
						return 'America/Mexico_City';
						break;
					case '22':
						return 'America/Mexico_City';
						break;
					case '23':
						return 'America/Cancun';
						break;
					case '24':
						return 'America/Mexico_City';
						break;
					case '25':
						return 'America/Mazatlan';
						break;
					case '26':
						return 'America/Hermosillo';
						break;
					case '27':
						return 'America/Merida';
						break;
					case '28':
						return 'America/Matamoros';
						break;
					case '29':
						return 'America/Mexico_City';
						break;
					case '30':
						return 'America/Mexico_City';
						break;
					case '31':
						return 'America/Merida';
						break;
					case '32':
						return 'America/Bahia_Banderas';
						break;
				}
				break;
			case 'MY':
				switch($region) {
					case '01':
						return 'Asia/Kuala_Lumpur';
						break;
					case '02':
						return 'Asia/Kuala_Lumpur';
						break;
					case '03':
						return 'Asia/Kuala_Lumpur';
						break;
					case '04':
						return 'Asia/Kuala_Lumpur';
						break;
					case '05':
						return 'Asia/Kuala_Lumpur';
						break;
					case '06':
						return 'Asia/Kuala_Lumpur';
						break;
					case '07':
						return 'Asia/Kuala_Lumpur';
						break;
					case '08':
						return 'Asia/Kuala_Lumpur';
						break;
					case '09':
						return 'Asia/Kuala_Lumpur';
						break;
					case '11':
						return 'Asia/Kuching';
						break;
					case '12':
						return 'Asia/Kuala_Lumpur';
						break;
					case '13':
						return 'Asia/Kuala_Lumpur';
						break;
					case '14':
						return 'Asia/Kuala_Lumpur';
						break;
					case '15':
						return 'Asia/Kuching';
						break;
					case '16':
						return 'Asia/Kuching';
						break;
				}
				break;
			case 'NZ':
				switch($region) {
					case '85':
						return 'Pacific/Auckland';
						break;
					case 'E7':
						return 'Pacific/Auckland';
						break;
					case 'E8':
						return 'Pacific/Auckland';
						break;
					case 'E9':
						return 'Pacific/Auckland';
						break;
					case 'F1':
						return 'Pacific/Auckland';
						break;
					case 'F2':
						return 'Pacific/Auckland';
						break;
					case 'F3':
						return 'Pacific/Auckland';
						break;
					case 'F4':
						return 'Pacific/Auckland';
						break;
					case 'F5':
						return 'Pacific/Auckland';
						break;
					case 'F6':
						return 'Pacific/Auckland';
						break;
					case 'F7':
						return 'Pacific/Chatham';
						break;
					case 'F8':
						return 'Pacific/Auckland';
						break;
					case 'F9':
						return 'Pacific/Auckland';
						break;
					case 'G1':
						return 'Pacific/Auckland';
						break;
					case 'G2':
						return 'Pacific/Auckland';
						break;
					case 'G3':
						return 'Pacific/Auckland';
						break;
				}
				break;
			case 'PT':
				switch($region) {
					case '02':
						return 'Europe/Lisbon';
						break;
					case '03':
						return 'Europe/Lisbon';
						break;
					case '04':
						return 'Europe/Lisbon';
						break;
					case '05':
						return 'Europe/Lisbon';
						break;
					case '06':
						return 'Europe/Lisbon';
						break;
					case '07':
						return 'Europe/Lisbon';
						break;
					case '08':
						return 'Europe/Lisbon';
						break;
					case '09':
						return 'Europe/Lisbon';
						break;
					case '10':
						return 'Atlantic/Madeira';
						break;
					case '11':
						return 'Europe/Lisbon';
						break;
					case '13':
						return 'Europe/Lisbon';
						break;
					case '14':
						return 'Europe/Lisbon';
						break;
					case '16':
						return 'Europe/Lisbon';
						break;
					case '17':
						return 'Europe/Lisbon';
						break;
					case '18':
						return 'Europe/Lisbon';
						break;
					case '19':
						return 'Europe/Lisbon';
						break;
					case '20':
						return 'Europe/Lisbon';
						break;
					case '21':
						return 'Europe/Lisbon';
						break;
					case '22':
						return 'Europe/Lisbon';
						break;
					case '23':
						return 'Atlantic/Azores';
						break;
				}
				break;
			case 'RU':
				switch($region) {
					case '01':
						return 'Europe/Volgograd';
						break;
					case '02':
						return 'Asia/Irkutsk';
						break;
					case '03':
						return 'Asia/Novokuznetsk';
						break;
					case '04':
						return 'Asia/Novosibirsk';
						break;
					case '05':
						return 'Asia/Vladivostok';
						break;
					case '06':
						return 'Europe/Moscow';
						break;
					case '07':
						return 'Europe/Volgograd';
						break;
					case '08':
						return 'Europe/Yekaterinburg';
						break;
					case '09':
						return 'Europe/Moscow';
						break;
					case '10':
						return 'Europe/Moscow';
						break;
					case '11':
						return 'Asia/Irkutsk';
						break;
					case '12':
						return 'Europe/Volgograd';
						break;
					case '13':
						return 'Asia/Yekaterinburg';
						break;
					case '14':
						return 'Asia/Irkutsk';
						break;
					case '15':
						return 'Asia/Anadyr';
						break;
					case '16':
						return 'Europe/Samara';
						break;
					case '17':
						return 'Europe/Volgograd';
						break;
					case '18':
						return 'Asia/Krasnoyarsk';
						break;
					case '20':
						return 'Asia/Irkutsk';
						break;
					case '21':
						return 'Europe/Moscow';
						break;
					case '22':
						return 'Europe/Volgograd';
						break;
					case '23':
						return 'Europe/Kaliningrad';
						break;
					case '24':
						return 'Europe/Volgograd';
						break;
					case '25':
						return 'Europe/Moscow';
						break;
					case '26':
						return 'Asia/Kamchatka';
						break;
					case '27':
						return 'Europe/Volgograd';
						break;
					case '28':
						return 'Europe/Moscow';
						break;
					case '29':
						return 'Asia/Novokuznetsk';
						break;
					case '30':
						return 'Asia/Sakhalin';
						break;
					case '31':
						return 'Asia/Krasnoyarsk';
						break;
					case '32':
						return 'Asia/Omsk';
						break;
					case '33':
						return 'Europe/Samara';
						break;
					case '34':
						return 'Asia/Yekaterinburg';
						break;
					case '35':
						return 'Asia/Yekaterinburg';
						break;
					case '36':
						return 'Asia/Magadan';
						break;
					case '37':
						return 'Europe/Moscow';
						break;
					case '38':
						return 'Europe/Volgograd';
						break;
					case '39':
						return 'Asia/Krasnoyarsk';
						break;
					case '40':
						return 'Asia/Yekaterinburg';
						break;
					case '41':
						return 'Europe/Moscow';
						break;
					case '42':
						return 'Europe/Moscow';
						break;
					case '43':
						return 'Europe/Moscow';
						break;
					case '44':
						return 'Asia/Magadan';
						break;
					case '45':
						return 'Europe/Samara';
						break;
					case '46':
						return 'Europe/Samara';
						break;
					case '47':
						return 'Europe/Moscow';
						break;
					case '48':
						return 'Europe/Moscow';
						break;
					case '49':
						return 'Europe/Moscow';
						break;
					case '50':
						return 'Asia/Yekaterinburg';
						break;
					case '51':
						return 'Europe/Moscow';
						break;
					case '52':
						return 'Europe/Moscow';
						break;
					case '53':
						return 'Asia/Novosibirsk';
						break;
					case '54':
						return 'Asia/Omsk';
						break;
					case '55':
						return 'Europe/Samara';
						break;
					case '56':
						return 'Europe/Moscow';
						break;
					case '57':
						return 'Europe/Samara';
						break;
					case '58':
						return 'Asia/Yekaterinburg';
						break;
					case '59':
						return 'Asia/Vladivostok';
						break;
					case '60':
						return 'Europe/Kaliningrad';
						break;
					case '61':
						return 'Europe/Volgograd';
						break;
					case '62':
						return 'Europe/Moscow';
						break;
					case '63':
						return 'Asia/Yakutsk';
						break;
					case '64':
						return 'Asia/Sakhalin';
						break;
					case '65':
						return 'Europe/Samara';
						break;
					case '66':
						return 'Europe/Moscow';
						break;
					case '67':
						return 'Europe/Volgograd';
						break;
					case '68':
						return 'Europe/Volgograd';
						break;
					case '69':
						return 'Europe/Moscow';
						break;
					case '70':
						return 'Europe/Volgograd';
						break;
					case '71':
						return 'Asia/Yekaterinburg';
						break;
					case '72':
						return 'Europe/Moscow';
						break;
					case '73':
						return 'Europe/Samara';
						break;
					case '74':
						return 'Asia/Yakutsk';
						break;
					case '75':
						return 'Asia/Novosibirsk';
						break;
					case '76':
						return 'Europe/Moscow';
						break;
					case '77':
						return 'Europe/Moscow';
						break;
					case '78':
						return 'Asia/Yekaterinburg';
						break;
					case '79':
						return 'Asia/Krasnoyarsk';
						break;
					case '80':
						return 'Asia/Yekaterinburg';
						break;
					case '81':
						return 'Europe/Samara';
						break;
					case '82':
						return 'Asia/Irkutsk';
						break;
					case '83':
						return 'Europe/Moscow';
						break;
					case '84':
						return 'Europe/Volgograd';
						break;
					case '85':
						return 'Europe/Moscow';
						break;
					case '86':
						return 'Europe/Moscow';
						break;
					case '87':
						return 'Asia/Omsk';
						break;
					case '88':
						return 'Europe/Moscow';
						break;
					case '89':
						return 'Asia/Vladivostok';
						break;
					case '90':
						return 'Asia/Yekaterinburg';
						break;
					case '91':
						return 'Asia/Krasnoyarsk';
						break;
					case '92':
						return 'Asia/Kamchatka';
						break;
					case '93':
						return 'Asia/Irkutsk';
						break;
					case 'CI':
						return 'Europe/Volgograd';
						break;
					case 'JA':
						return 'Asia/Sakhalin';
						break;
				}
				break;
			case 'UA':
				switch($region) {
					case '01':
						return 'Europe/Kiev';
						break;
					case '02':
						return 'Europe/Kiev';
						break;
					case '03':
						return 'Europe/Uzhgorod';
						break;
					case '04':
						return 'Europe/Zaporozhye';
						break;
					case '05':
						return 'Europe/Zaporozhye';
						break;
					case '06':
						return 'Europe/Uzhgorod';
						break;
					case '07':
						return 'Europe/Zaporozhye';
						break;
					case '08':
						return 'Europe/Simferopol';
						break;
					case '09':
						return 'Europe/Kiev';
						break;
					case '10':
						return 'Europe/Zaporozhye';
						break;
					case '11':
						return 'Europe/Simferopol';
						break;
					case '12':
						return 'Europe/Kiev';
						break;
					case '13':
						return 'Europe/Kiev';
						break;
					case '14':
						return 'Europe/Zaporozhye';
						break;
					case '15':
						return 'Europe/Uzhgorod';
						break;
					case '16':
						return 'Europe/Zaporozhye';
						break;
					case '17':
						return 'Europe/Simferopol';
						break;
					case '18':
						return 'Europe/Zaporozhye';
						break;
					case '19':
						return 'Europe/Kiev';
						break;
					case '20':
						return 'Europe/Simferopol';
						break;
					case '21':
						return 'Europe/Kiev';
						break;
					case '22':
						return 'Europe/Uzhgorod';
						break;
					case '23':
						return 'Europe/Kiev';
						break;
					case '24':
						return 'Europe/Uzhgorod';
						break;
					case '25':
						return 'Europe/Uzhgorod';
						break;
					case '26':
						return 'Europe/Zaporozhye';
						break;
					case '27':
						return 'Europe/Kiev';
						break;
				}
				break;
			case 'UZ':
				switch($region) {
					case '01':
						return 'Asia/Tashkent';
						break;
					case '02':
						return 'Asia/Samarkand';
						break;
					case '03':
						return 'Asia/Tashkent';
						break;
					case '05':
						return 'Asia/Samarkand';
						break;
					case '06':
						return 'Asia/Tashkent';
						break;
					case '07':
						return 'Asia/Samarkand';
						break;
					case '08':
						return 'Asia/Samarkand';
						break;
					case '09':
						return 'Asia/Samarkand';
						break;
					case '10':
						return 'Asia/Samarkand';
						break;
					case '12':
						return 'Asia/Samarkand';
						break;
					case '13':
						return 'Asia/Tashkent';
						break;
					case '14':
						return 'Asia/Tashkent';
						break;
				}
				break;
			case 'SH':
				return 'Atlantic/St_Helena';
				break;
			case 'CC':
				return 'Indian/Cocos';
				break;
			case 'TF':
				return 'Indian/Kerguelen';
				break;
			case 'SJ':
				return 'Arctic/Longyearbyen';
				break;
			case 'GS':
				return 'Atlantic/South_Georgia';
				break;
			case 'CX':
				return 'Indian/Christmas';
				break;
			case 'PN':
				return 'Pacific/Pitcairn';
				break;
			case 'MF':
				return 'America/Marigot';
				break;
			case 'BL':
				return 'America/St_Barthelemy';
				break;
			case 'EH':
				return 'Africa/El_Aaiun';
				break;
			case 'TL':
				return 'Asia/Dili';
				break;
			case 'PF':
				return 'Pacific/Marquesas';
				break;
			case 'SX':
				return 'America/Curacao';
				break;
			case 'BQ':
				return 'America/Curacao';
				break;
			case 'CW':
				return 'America/Curacao';
				break;
		}
		return false;
	}

}