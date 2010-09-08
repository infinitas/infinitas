<?php
	/*
	 * Generic Json data fetcher.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package libs
	 * @subpackage libs.models.datasources.json
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	App::import('Datasource', 'Libs.ReaderSource');
	class JsonSource extends ReaderSource {

		/**
		 * Process json.
		 *
		 * Takes json and convers it to an array using json_decode
		 *
		 * @param string $response the json that will be converted
		 *
		 * @return array the data from json or empty array if there is nothing passed
		 */
		protected function _process($response = null) {
			if (empty($response)) {
				return array();
			}

			$_response = json_decode($response);
			if(!empty($_response)){
				$response = $_response;
			}
			
			// @todo app::import modeless AppModel and use the Infinitas->getJson() method.
			return $response;
		}
	}