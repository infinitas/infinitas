<?php
/**
 * TimeZone
 *
 * @package Infinitas.Contact.Model
 */

/**
 * TimeZone
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Contact.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class TimeZone extends ContactAppModel {
/**
 * Remove table prefix
 *
 * @var string
 */
	public $tablePrefix = '';

/**
 * Disable model loading table
 *
 * @var boolean
 */
	public $useTable = false;

/**
 * Get timezones
 *
 * @param string $type type of data to return
 *
 * @return array
 */
	public function find($type = 'first', $query = array()) {
		$return = timezone_identifiers_list();

		switch($type) {
			case 'list':
				return $return;
				break;

			case 'all':
				foreach ($return as $key => $value) {
					$data[] = array(
						'TimeZone' => array(
							'id' => $key,
							'name' => $value
						)
					);
				}
				return $data;
				break;
		}
	}

}