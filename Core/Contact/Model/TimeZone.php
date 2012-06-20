<?php
	class TimeZone extends ContactAppModel {
		public $tablePrefix = '';

		public $useTable = false;

		public function find($type) {
			$return = timezone_identifiers_list();

			switch($type) {
				case 'list':
					return $return;
					break;

				case 'all':
					foreach($return as $key => $value) {
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