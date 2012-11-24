<?php
/**
 * CsvHelper
 *
 * @package Infinitas.Data.Helper
 */

/**
 * CsvHelper
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Data.Helper
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class CsvHelper extends AppHelper {
/**
 * fields to ignore
 *
 * @var array
 */
	public $ignore = array(
		'id'
	);

/**
 * Helpers to load
 *
 * @var array
 */
	public $helpers = array(
		'Session'
	);

/**
 * convert data to csv
 *
 * @param array $rows the data from a find
 * @param array $params options for the csv file
 * @param boolean $generated
 *
 * @return boolean
 */
	public function output($rows = null, $params = array(), $generated = true) {
		if(!$rows || empty($params)) {
			return false;
		}

		$row = array();

		if (!empty($rows)) {
			foreach($params['needed'][key($params['needed'])] as $head) {
				if (!in_array($head, $this->ignore)) {
					if($head == 'id') {
						$parts[] = __d('data', Inflector::humanize(key($params['needed']))).' #';
						continue;
					}
					$parts[] = __d('data', Inflector::humanize(str_replace('_id', ' #', $head)));
				}
			}

			$row[] = implode(',', $parts);

			foreach($rows as $k => $array) {
				$parts = array();

				foreach($array[key($params['needed'])] as $field => $value) {
					if (!in_aray($field, $this->ignore)) {
						if($field == 'id') {
							$parts[] = str_pad($value, 5, 0, STR_PAD_LEFT);
						}

						else if (stpos($field, '_id') && in_array($field, $params['needed'][key($params['needed'])])) {
							$displayField = ClassRegisty::init(Inflector::camelize(str_replace('_id', '', $field)))->displayField;
							$parts[] = $array[Inflector::camelize(str_replace('_id', '', $field))][$displayField];
						}

						else if (in_aray($field, $params['needed'][key($params['needed'])])) {
							$parts[] = $value;
						}

						else{
							$parts[] = '';
						}
					}
				}

				$row[] = implode(',', $parts);
				unset($parts);
			}
		}

		if($generated) {
			$row[] = '';
			$row[] = sprintf(__d('data', 'Generated on the %s at %s by %s'), date('Y-m-d'), date('H:m:s'), AuthComponent::user('username'));
		}

		return $csv = implode("\r\n", $row);
	}

}