<?php
	class TimeZone extends ManagementAppModel{
		var $name = 'TimeZone';

		var $tablePrefix = '';
		var $useTable = false;

		function find($type){
			$return = timezone_identifiers_list();

			switch($type){
				case 'list':
					return $return;
					break;

				case 'all':
					foreach($return as $key => $value){
						$data[] = array(
							'TimeZone' => array(
								'id' => $key,
								'name' => $value
							)
						);
					}
					return $data;
					break;
			} // switch
		}
	}
?>