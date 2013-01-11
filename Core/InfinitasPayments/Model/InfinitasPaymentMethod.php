<?php
/**
 * InfinitasPaymentMethod
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link http://infinitas-cms.org/InfinitasPayments
 * @package	InfinitasPayments.Model
 * @license	http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author dogmatic69
 */

class InfinitasPaymentMethod extends InfinitasPaymentsAppModel {

	public $findMethods = array(
		'config' => true
	);

/**
 * hasMany relations for this model
 *
 * @var array
 */
	public $hasMany = array(
		'InfinitasPaymentLog' => array(
			'className' => 'InfinitasPaymentLog',
			'foreignKey' => 'infinitas_payment_method_id',
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

		$this->validate = array(
			'name' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('infinitas_payments', 'Please enter a name for this payment method')
				),
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('infinitas_payments', 'There is already a payment method with this name')
				)
			)
		);
	}

	protected function _findConfig($state, array $query, array $results = array()) {
		if ($state == 'before') {
			if (empty($query[0])) {
				throw new InvalidArgumentException(__d('infinitas_payments', 'No provider selected'));
			}

			$query['fields'] = array(
				$this->alias . '.name',
				$this->alias . '.slug',
				$this->alias . '.provider',
				$this->alias . '.live',
				$this->alias . '.sandbox',
				$this->alias . '.testing'
			);

			$query['conditions'] = array(
				$this->alias . '.slug' => $query[0],
				$this->alias . '.active' => true
			);

			return $query;
		}

		if (empty($results)) {
			throw new CakeException(__d('infinitas_payments', 'Provider config not found'));
		}

		$results = $results[0][$this->alias];
		$results['live'] = json_decode($results['live']);
		$results['sandbox'] = json_decode($results['sandbox']);

		return $results;
	}
}
