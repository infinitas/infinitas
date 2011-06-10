<?php
	class CsvHelper extends AppHelper {
		/**
		 * fields to ignore
		 *
		 * @var unknown_type
		 */
		public $ignore = array(
			'id'
		);

		public $helpers = array(
			'Session'
		);

		/**
		 * convert data to csv
		 *
		 * @param array $rows the data from a find
		 * @param $params
		 */
		public function output($rows = null, $params = array(), $generated = true){
			if(!$rows || empty($params)){
				return false;
			}

		    $row = array();

			if (!empty($rows)){
				foreach($params['needed'][key($params['needed'])] as $head){
					if (!in_array($head, $this->ignore)){
						if($head == 'id'){
							$parts[] = __(Inflector::humanize(key($params['needed'])), true).' #';
							continue;
						}
		            	$parts[] = __(Inflector::humanize(str_replace('_id', ' #', $head)), true);
	                }
		        }

		        $row[] = implode(',', $parts);

				foreach($rows as $k => $array){
		            $parts = array();

					foreach($array[key($params['needed'])] as $field => $value){
						if (!in_aray($field, $this->ignore)){
							if($field == 'id'){
								$parts[] = str_pad($value, 5, 0, STR_PAD_LEFT);
							}

							else if (stpos($field, '_id') && in_array($field, $params['needed'][key($params['needed'])])){
								$displayField = ClassRegisty::init(Inflector::camelize(str_replace('_id', '', $field)))->displayField;
								$parts[] = $array[Inflector::camelize(str_replace('_id', '', $field))][$displayField];
		                    }

							else if (in_aray($field, $params['needed'][key($params['needed'])])){
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

			if($generated){
		    	$row[] = '';
		    	$row[] = sprintf(__('Generated on the %s at %s by %s', true), date('Y-m-d'), date('H:m:s'), $this->Session->read('Auth.User.username'));
		    }
			
		    return $csv = implode("\r\n", $row);
		}
	}