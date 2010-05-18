<?php
	class CsvHelper extends AppHelper{
		/**
		 * fields to ignore
		 *
		 * @var unknown_type
		 */
		var $ignore = array(
			'id'
		);

		var $helpers = array(
			'Session'
		);

		/**
		 * convert data to csv
		 *
		 * @param array $rows the data from a find
		 * @param $params
		 */
		function output($rows = null, $params = array(), $generated = true){
			if(!$rows || empty($params)){
				return false;
			}

		    $row = array();

		    if (!empty($rows)){
		        foreach($params['needed'][key($params['needed'])] as $head){
	                if (!in_array($head, $this->ignore)){
		            	$parts[] = Inflector::humanize(str_replace('_id', ' #', $head));
	                }
		        }

		        $row[] = implode(',', $parts);

		        foreach($rows as $k => $array){
		            $parts = array();

		            foreach($array[key($params['needed'])] as $field => $value){
		                if (!in_array($field, $this->ignore)){
		                    if (strpos($field, '_id') && in_array($field, $params['needed'][key($params['needed'])])){
		                        $parts[] = $array[Inflector::camelize(str_replace('_id', '' , $field))][ClassRegistry::init(Inflector::camelize(str_replace('_id', '' , $field)))->displayField];
		                    }

		                    else if (in_array($field, $params['needed'][key($params['needed'])])){
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