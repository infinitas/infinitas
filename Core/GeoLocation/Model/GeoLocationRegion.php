<?php
/**
 * GeoLocationRegion
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

class GeoLocationRegion extends GeoLocationAppModel {

/**
 * custom find methods
 * 
 * @var array
 */
	public $findMethods = array(
		'regions' => true
	);

/**
 * belongsTo relations for this model
 *
 * @var array
 */
	public $belongsTo = array(
		'GeoLocationCountry' => array(
			'className' => 'GeoLocation.GeoLocationCountry',
			'foreignKey' => 'geo_location_country_id',
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'counterScope' => array(
				'GeoLocationRegion.active' => 1
			),
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
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
			'code' => array(
				'notempty' => array(
					'rule' => array('notempty'),
					//'message' => 'Your custom message here',
					//'allowEmpty' => false,
					//'required' => false,
					//'last' => false, // Stop validation after this rule
					//'on' => 'create', // Limit validation to 'create' or 'update' operations
				),
			),
		);
	}

/**
 * find regions by country
 *
 * @param string $state before / after
 * @param array $query the find conditions
 * @param array $results the find results
 *
 * @return array
 */
	protected function _findRegions($state, array $query, array $results = array()) {
		if ($state == 'before') {
			if (empty($query[0])) {
				throw new InvalidArgumentException(__d('geo_location', 'No country specified'));
			}

			$query['conditions'] = array_merge((array)$query['conditions'], array(
				$this->alias .'.geo_location_country_id' => $query[0],
				$this->alias . '.active' => 1
			));

			$query['fields'] = array(
				$this->alias . '.' . $this->primaryKey,
				$this->alias . '.' . $this->displayField,
			);

			$query['order'] = array(
				$this->alias . '.' . $this->displayField
			);

			if (empty($query['joins'])) {
				$query['joins'] = array();
			}
			$query['joins'] = (array)$query['joins'];

			$join = $this->autoJoinModel(array(
				'model' => $this->GeoLocationCountry,
				'type' => 'right'
			));
			$join['conditions'][$this->GeoLocationCountry->alias . '.active'] = 1;

			$query['joins'][] = $join;

			return $query;
		}

		$template = sprintf('{n}.%s.%%s', $this->alias);
		$results = Hash::combine($results, sprintf($template, $this->primaryKey), sprintf($template, $this->displayField));
		natcasesort($results);
		return $results;
	}
}
