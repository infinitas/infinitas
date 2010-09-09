<?php
	/*
	 * Generic xml data fetcher.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package libs
	 * @subpackage libs.models.datasources.xml
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	App::import('Datasource', 'Libs.ReaderSource');
	class XmlSource extends ReaderSource {

		/**
		 * Process xml.
		 *
		 * Takes xml and convers it to an array using cakes xml class
		 *
		 * @param string $response the xml that will be converted
		 *
		 * @return array the data from xml or empty array if there is nothing passed
		 */
		protected function _process($response = null) {
			if (empty($response)) {
				return array();
			}

			App::import('Xml');
			$this->Xml = new Xml($response);

			$data = $this->Xml->toArray();
			$this->Xml->__destruct();
			unset($this->Xml);

			return $data;
		}
	}