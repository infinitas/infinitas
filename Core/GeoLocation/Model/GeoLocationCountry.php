<?php
/**
 * GeoLocationCountry
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link http://infinitas-cms.org/GeoLocation
 * @package	GeoLocation.Model
 * @license	http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 */

class GeoLocationCountry extends GeoLocationAppModel {

/**
 * custom find methods
 *
 * @var array
 */
	public $findMethods = array(
		'countries' => true
	);

/**
 * hasMany relations for this model
 *
 * @var array
 */
	public $hasMany = array(
		'GeoLocationRegion' => array(
			'className' => 'GeoLocationRegion',
			'foreignKey' => 'geo_location_country_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

/**
 * Constructor
 *
 * @param string|integer $id string uuid or id
 * @param string $table the table that the model is for
 * @param string $ds the datasource being used
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->order = array(
			$this->alias . '.' . $this->displayField => 'asc'
		);

		$this->validate = array(
			'name' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __d('geo_location', 'Country name required'),
				),
				'isUnique' => array(
					'rule' => array('isUnique'),
					'message' => __d('geo_location', 'That country already exists')
				),
			),
			'code_2' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __d('geo_location', 'Two letter country code required')
				),
			),
			'code_3' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					'message' => __d('geo_location', 'Three letter country code required')
				),
			),
			'postcode_required' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('geo_location', 'Invalid param specified')
				),
			),
		);
	}

/**
 * find active countries
 *
 * Main countries for the application can be configured to show at the top of a select list,
 * When this is done group-options are used. If no 'main' countries are configured a flat list
 * is used. The 'all' group will always contain all available countries
 *
 * @param string $state before / after
 * @param array $query the find conditions
 * @param array $results the find results
 *
 * @return array
 */
	protected function _findCountries($state, array $query, array $results = array()) {
		if ($state == 'before') {
			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias . '.active' => 1
			));

			$query['fields'] = array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.' . $this->displayField,
				$this->alias . '.main',
			);

			return $query;
		}

		$main = __d('geo_location', 'Main');
		$all = __d('geo_location', 'All');
		$return = array(
			$main => array(),
			$all => array()
		);

		foreach ($results as $result) {
			$k = $result[$this->alias][$this->primaryKey];
			$v = $result[$this->alias][$this->displayField];
			if ($result[$this->alias]['main']) {
				$return[$main][$k] = $v;
			} 
			$return[$all][$k] = $v;
		}

		if (empty($return[$main])) {
			return $return[$all];
		}

		return $return;
	}

}
