<?php
	class ChartDataManipulation extends Object{
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
				$return['model'] = __('All', true);
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
				trigger_error(sprintf(__('data mismach for model: %s fields: (%s)', true), $return['model'], implode(', ', $fields)), E_USER_WARNING);
			}

			unset($data);
			return $return;
		}

		public function normalize($data, $base = 100){
			
		}

		public function getFormatted($data, $options){
			$_default = array(
				'fields' => '',
				'alias' => null,
				'range' => null,
				'blank_field' => null,
				'insert' => 'after',
				'date_field' => 'created',
				'extract' => null
			);

			$options = array_merge($_default, (array)$options);
			
			$return = array();
			$return = $this->getDates($data, $options);
			$return = array_merge($return, $this->getData($data, $options));
			
			$options['fields'] = $options['blank_field'];
			$return = array_merge($return, $this->getData($data, $options));

			unset($data, $options, $_default);

			return $return;
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
			if(!$options['extract'] && $options['alias']){
				$options['extract'] = '/' . $options['alias'] . '/' . $options['date_field'];
			}

			if(!$options['extract']){
				return array();
			}

			$return = array();
			
			$dates = Set::extract($options['extract'], $data);
			$return['start_date'] = !empty($dates) ? min($dates) : '';
			$return['end_date'] = !empty($dates) ? max($dates) : '';

			unset($data, $options);
			return $return;
		}

		public function getData($data, $options){
			if(!$options['extract'] && $options['alias']){
				$options['extract'] = '/' . $options['alias'] . '/%s';
			}

			if(!$options['extract']){
				return array();
			}

			if(!is_array($options['fields'])){
				$options['fields'] = array($options['fields']);
			}

			$return = array();
			foreach($options['fields'] as $field){
				$return[$field] = $this->getBlanks(
					Set::extract(sprintf($options['extract'], $field), $data), $options
				);
			}

			unset($data, $options);
			return $return;
		}

		public function getBlanks($data, $options){
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
						return array($options['blank_field'] => array());
						break;
				}
			}			

			return $data;
		}
	}