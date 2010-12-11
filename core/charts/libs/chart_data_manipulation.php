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
	}