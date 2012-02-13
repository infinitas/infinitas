<?php
	class ChartDataManipulation extends Object{
		/**
		 *
		 * @deprecated
		 *
		 * @param <type> $data
		 * @param <type> $range
		 * @param <type> $field
		 * @return int
		 */
		public function fillBlanks($data, $range, $field){
			if(empty($data['totals']) || empty($range) || !is_array($range) || empty($field) || !is_string($field)){
				return $data;
			}

			$data['totals'] = array_combine($data[$field], $data['totals']);
			foreach($range as $v){
				$data[$field][$v - 1] = $v;
				if(!isset($data['totals'][$v - 1])){
					$data['totals'][$v - 1] = 0;
				}
			}
			unset($data['totals'][0], $range, $field);
			ksort($data['totals']);

			return $data;
		}

		/**
		 * Format the data ready for the google charts helper by extracting the
		 * sub_totals into one array and the 'value' field into another (for lables)
		 *
		 * Some other info is also added to the return array like totals and what
		 * model its for.
		 *
		 * @deprecated
		 *
		 * @param <type> $data
		 * @param <type> $field
		 * @return <type>
		 */
		public function formatData($alias, $data, $fields){
			if(!is_string($alias) || !is_array($data) || empty($data) || empty($fields)){
				return false;
			}

			if(!is_array($fields)){
				$fields = array($fields);
			}

			$return = array();
			$return['totals'] = array();
			foreach($fields as $field){
				$fieldName = $field;
				$return[$field] = array();
			}

			if(!empty($data)){
				$return['model'] = __('All');
				if(isset($data[0][$alias]['model'])){
					$return['model'] = $data[0][$alias]['model'];
				}
				$return['totals'] = Set::extract('/' . $alias . '/sub_total', $data);

				foreach($fields as $field){
					$fieldName = Inflector::pluralize($field);
					$return[$fieldName] = Set::extract('/' . $alias . '/' . $field, $data);
				}
			}


			$dates = Set::extract('/' . $alias . '/created', $data);
			$return['start_date'] = !empty($dates) ? min($dates) : array();
			$return['end_date'] = !empty($dates) ? max($dates) : array();

			$return['total_views'] = array_sum((array)$return['totals']);
			$return['total_rows']  = count($return['totals']);

			if(isset($return[$fieldName]) && $return['total_rows'] != count($return[$fieldName])){
				trigger_error(sprintf(__('data mismach for model: %s fields: (%s)'), $return['model'], implode(', ', $fields)), E_USER_WARNING);
			}

			unset($data);
			return $return;
		}

		public function getFormatted($data, $options){
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
		 * @brief normalize data
		 * 
		 * @param <type> $data
		 * @param <type> $options
		 * @return <type>
		 */
		public function _normalize($data, $options){
			if(!$options['normalize']){
				return $data;
			}
			
			$round = isset($options['normalize']['round']) ? (int)$options['normalize']['round'] : 0;
			$base = isset($options['normalize']['base']) ? (int)$options['normalize']['base'] : (int)$options['normalize'];

			foreach($data as $field => $values){
				$max = max($values);
				foreach($values as $k => $v){
					$data[$field][$k] = round(($v / $max) * $base, $round);
				}
			}

			return $data;
		}

		/**
		 * @brief get some stats on the numbers passed in
		 * 
		 * @param <type> $data
		 * @param <type> $options
		 * @return <type>
		 */
		public function _stats($data, $options){
			if(!$options['stats']){
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
		 * @brief get the average of an array of numbers
		 * 
		 * @param <type> $values
		 * @return <type>
		 */
		protected function _average($values){
			return array_sum($values) / count($values);
		}

		/**
		 * @brief get the median of an array of numbers
		 * 
		 * @param <type> $values
		 * @return <type>
		 */
		protected function _median($values){
			sort($values);
			$count = count($values);
			$mid = intval($count / 2);
			
			return ($count % 2 == 0) ? ($values[$mid] + $values[$mid - 1]) / 2 : $values[$mid];
		}

		/**
		 * @brief extract the min and max date from the data
		 *
		 * @param array $data the array of data from the model
		 * @param string $options options for the extraction
		 *	@li date_field - where the dates are extracted from
		 *	@li alias - the alias of the model where the dates are extracted from
		 *	@li extract - if nothing is set a defult of /{alias}/{date_field} is used
		 * @access public
		 *
		 * @return array the dates
		 */
		public function getDates($data, $options = array()){
			$options = $this->_defaults($options);

			if($options['alias'] && $options['date_field']){
				$options['extract'] = '/' . $options['alias'] . '/' . $options['date_field'];
			}

			if(!$options['extract']){
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
		 * @brief extract the data from the array
		 * 
		 * @param <type> $data
		 * @param <type> $options
		 * @return <type>
		 */
		public function getData($data, $options){
			$options = $this->_defaults($options);
			
			if(!$options['extract'] && $options['alias']){
				$options['extract'] = '/' . $options['alias'] . '/%s';
			}

			if(!$options['extract'] || empty($options['fields'])){
				return array();
			}

			if(!is_array($options['fields'])){
				$options['fields'] = array($options['fields']);
			}
			
			$_data = array();
			foreach($options['fields'] as $field){
				$_data[$field] = Set::extract(sprintf($options['extract'], $field), $data);
			}
			unset($data);

			$_data = $this->_normalize($_data, $options);
			$return = $this->_stats($_data, $options);

			foreach($_data as $field => $values){
				$return[$field] = $this->getBlanks($values, $options);
			}

			unset($_data, $options);
			return $return;
		}

		/**
		 * @brief normalizes the data
		 * 
		 * @param <type> $data
		 * @param <type> $options
		 * @return <type>
		 */
		public function getBlanks($data, $options){
			$options = $this->_defaults($options);
			
			if(!$options['blanks']){
				return $data;
			}

			if(!is_array($options['range'])){
				return array();
			}
			
			if(count($data) != count($options['range'])){
				switch($options['insert']){
					case 'before':
						while(count($data) < count($options['range'])){
							array_unshift($data, 0);
						}
						break;

					case 'after':
						while(count($data) < count($options['range'])){
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
		 * @brief sets all values to a default so that no checks need to be done
		 * 
		 * @param <type> $options
		 * @return <type>
		 */
		protected function _defaults($options){
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
			if($options['normalize'] && is_bool($options['normalize'])){
				$options['normalize'] = 100;
			}

			return $options;
		}
	}