<?php
/**
 * Chart data manipulation class
 *
 * @link http://infinitas-cms.org/infinitas_docs/Charts Infinitas Charts
 *
 * @package Infinitas.Charts.Lib
 */

/**
 * Chart data manipulation class
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org/infinitas_docs/Charts Infinitas Charts
 * @package Infinitas.Charts.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ChartDataManipulation extends Object{
/**
 * Fill blanks
 *
 * @deprecated
 *
 * @param array $data the data
 * @param string $range array to fill with
 * @param array $field field to fill
 *
 * @return array
 */
	public function fillBlanks($data, $range, $field) {
		if(empty($data['totals']) || empty($range) || !is_array($range) || empty($field) || !is_string($field)) {
			return $data;
		}

		$data['totals'] = array_combine($data[$field], $data['totals']);
		foreach($range as $v) {
			$data[$field][$v - 1] = $v;
			if(!isset($data['totals'][$v - 1])) {
				$data['totals'][$v - 1] = 0;
			}
		}
		unset($data['totals'][0], $range, $field);
		ksort($data['totals']);

		return $data;
	}

/**
 * Format data
 *
 * Format the data ready for the google charts helper by extracting the
 * sub_totals into one array and the 'value' field into another (for lables)
 *
 * Some other info is also added to the return array like totals and what
 * model its for.
 *
 * @deprecated
 *
 * @param string $alias the model alias
 * @param array $data the data
 * @param array $fields fields to format
 *
 * @return array
 */
	public function formatData($alias, $data, $fields) {
		if(!is_string($alias) || !is_array($data) || empty($data) || empty($fields)) {
			return false;
		}

		if(!is_array($fields)) {
			$fields = array($fields);
		}

		$return = array();
		$return['totals'] = array();
		foreach($fields as $field) {
			$fieldName = $field;
			$return[$field] = array();
		}

		if(!empty($data)) {
			$return['model'] = __('All');
			if(isset($data[0][$alias]['model'])) {
				$return['model'] = $data[0][$alias]['model'];
			}
			$return['totals'] = Set::extract('/' . $alias . '/sub_total', $data);

			foreach($fields as $field) {
				$fieldName = Inflector::pluralize($field);
				$return[$fieldName] = Set::extract('/' . $alias . '/' . $field, $data);
			}
		}


		$dates = Set::extract('/' . $alias . '/created', $data);
		$return['start_date'] = !empty($dates) ? min($dates) : array();
		$return['end_date'] = !empty($dates) ? max($dates) : array();

		$return['total_views'] = array_sum((array)$return['totals']);
		$return['total_rows']  = count($return['totals']);

		if(isset($return[$fieldName]) && $return['total_rows'] != count($return[$fieldName])) {
			trigger_error(sprintf(__('data mismach for model: %s fields: (%s)'), $return['model'], implode(', ', $fields)), E_USER_WARNING);
		}

		unset($data);
		return $return;
	}

/**
 * Get formatted data
 *
 * @param array $data the data being formatted
 * @param array $options the formatting options
 *
 * @return array
 */
	public function getFormatted($data, $options) {
		$options = $this->_defaults($options);
		$return = $this->getDates($data, $options);
		$return = array_merge($return, $this->getData($data, $options));

		$options['fields'] = $options['blank_field'];
		$options['stats'] = false;
		$return = array_merge($return, $this->getData($data, $options));

		unset($data, $options);

		return $return;
	}

/**
 * Normalize data
 *
 * @param array $data the data
 * @param array $options options for normalizing
 *
 * @return array
 */
	public function _normalize($data, $options) {
		if(!$options['normalize']) {
			return $data;
		}

		$round = isset($options['normalize']['round']) ? (int)$options['normalize']['round'] : 0;
		$base = isset($options['normalize']['base']) ? (int)$options['normalize']['base'] : (int)$options['normalize'];

		foreach($data as $field => $values) {
			$max = max($values);
			foreach($values as $k => $v) {
				$data[$field][$k] = round(($v / $max) * $base, $round);
			}
		}

		return $data;
	}

/**
 * get some stats on the numbers passed in
 *
 * @param array $data the data values
 * @param array $options stats options
 *
 * @return array
 */
	public function _stats($data, $options) {
		if(!$options['stats']) {
			return $data;
		}

		$empty = array('max' => 0, 'min' => 0, 'total' => 0, 'average' => 0, 'median' => 0);
		$return = array('stats' => $empty);

		foreach($data as $field => $values) {

			if(empty($values)) {
				$return['stats'][$field] = $empty;
				continue;
			}

			$return['stats'][$field]['max']     = max($values);
			$return['stats'][$field]['min']     = min($values);
			$return['stats'][$field]['total']   = array_sum($values);
			$return['stats'][$field]['average'] = $this->_average($values);
			$return['stats'][$field]['median']  = $this->_median($values);
		}

		$values = Set::flatten($data);

		if($values) {
			$return['stats']['max']     = max($values);
			$return['stats']['min']     = min($values);
			$return['stats']['total']   = array_sum($values);
			$return['stats']['average'] = $this->_average($values);
			$return['stats']['median']  = $this->_median($values);
		}

		unset($data, $values);
		return $return;
	}

/**
 * Get the average of an array of numbers
 *
 * @param array $values the data values
 *
 * @return float
 */
	protected function _average($values) {
		return array_sum($values) / count($values);
	}

/**
 * Get the median of an array of numbers
 *
 * @param array $values the data values
 *
 * @return float
 */
	protected function _median($values) {
		sort($values);
		$count = count($values);
		$mid = intval($count / 2);
		if($count % 2 == 0) {
			return ((int)$values[$mid] + (int)$values[$mid - 1]) / 2;
		}

		return (int)$values[$mid];
	}

/**
 * Extract the min and max date from the data
 *
 * @param array $data the array of data from the model
 * @param string $options options for the extraction
 *
 * * date_field - where the dates are extracted from
 * * alias - the alias of the model where the dates are extracted from
 * * extract - if nothing is set a defult of /{alias}/{date_field} is used
 *
 * @return array
 */
	public function getDates($data, $options = array()) {
		$options = $this->_defaults($options);

		if($options['alias'] && $options['date_field']) {
			$options['extract'] = '/' . $options['alias'] . '/' . $options['date_field'];
		}

		if(!$options['extract']) {
			return array();
		}

		$return = array();
		$dates = Set::extract($options['extract'], $data);
		$return['start_date'] = !empty($dates) ? date('Y-m-d H:i:s', strtotime(min($dates))) : '';
		$return['end_date']   = !empty($dates) ? date('Y-m-d H:i:s', strtotime(max($dates))) : '';

		unset($data, $options);
		return $return;
	}

/**
 * Extract the data from the array
 *
 * @param array $data the data
 * @param array $options options for the extract
 *
 * @return array
 */
	public function getData($data, $options) {
		$options = $this->_defaults($options);

		if(!$options['extract'] && $options['alias']) {
			$options['extract'] = '/' . $options['alias'] . '/%s';
		}

		if(!$options['extract'] || empty($options['fields'])) {
			return array();
		}

		if(!is_array($options['fields'])) {
			$options['fields'] = array($options['fields']);
		}

		$_data = array();
		foreach($options['fields'] as $field) {
			$_data[$field] = Set::extract(sprintf($options['extract'], $field), $data);
		}
		unset($data);

		$_data = $this->_normalize($_data, $options);
		$return = $this->_stats($_data, $options);

		foreach($_data as $field => $values) {
			$return[$field] = $this->getBlanks($values, $options);
		}

		unset($_data, $options);
		return $return;
	}

/**
 * Fill out data blanks
 *
 * * blanks: Fill in blank values (used with dates)
 * * range: Get a range of data
 * * insert: how the data is inserted (before or after)
 *
 * @param array $data the data being normalized
 * @param array $options the normalisation options
 *
 * @return array
 */
	public function getBlanks($data, $options) {
		$options = $this->_defaults($options);

		if(!$options['blanks']) {
			return $data;
		}

		if(!is_array($options['range'])) {
			return array();
		}

		if(count($data) != count($options['range'])) {
			switch($options['insert']) {
				case 'before':
					while(count($data) < count($options['range'])) {
						array_unshift($data, 0);
					}
					break;

				case 'after':
					while(count($data) < count($options['range'])) {
						array_push($data, 0);
					}
					break;

				default:
					return array();
					break;
			}
		}

		return $data;
	}

/**
 * Sets all values to a default so that no checks need to be done
 *
 * @param array $options options to be merged with defaults
 *
 * @return array
 */
	protected function _defaults($options) {
		$_default = array(
			'fields' => '',
			'blanks' => true,
			'alias' => null,
			'range' => null,
			'blank_field' => null,
			'insert' => 'after',
			'date_field' => 'created',
			'extract' => null,
			'normalize' => false,
			'stats' => false,
			'normalize' => false
		);

		$options = array_merge($_default, (array)$options);
		if($options['normalize'] && is_bool($options['normalize'])) {
			$options['normalize'] = 100;
		}

		return $options;
	}

}