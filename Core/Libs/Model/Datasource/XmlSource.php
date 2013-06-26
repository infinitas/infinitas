<?php
/*
 * Generic xml data fetcher.
 *
 * @filesource
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Libs.Model.Datasource
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
App::uses('ReaderSource', 'Libs.Model/Datasource');
App::uses('Xml', 'Utility');

class XmlSource extends ReaderSource {

	/**
	 * Process xml.
	 *
	 * Takes xml and convers it to an array using cakes xml class
	 *
	 * @param string $response the xml that will be converted
	 *
	 * @return array
	 */
	protected function _process($response = null) {
		if (empty($response)) {
			return array();
		}

		return Xml::toArray(Xml::build($response));
	}

}