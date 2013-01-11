<?php

class InfinitasGateway {

	public static $PENDING = 0;

	public static $PAID = 1;

	public static $CANCELED = -1;

	public static $ERROR = -2;

/**
 * Instance of the payment class
 *
 * @var PaymentSocket
 */
	protected $_Payment;

/**
 * The order index
 *
 * @var integer
 */
	protected $_orderIndex = 0;

/**
 * The orders for this payment
 *
 * Some processors allow making many transactions at a time such as PayPal, so we can collect details
 * for multiple orders here for processing
 *
 * @var array
 */
	protected $_orders = array();

/**
 * The default fields that are available when processing payments.
 *
 * This is a generic list of what fields that can be used that the payment gateway will be expecting
 *
 * @var array
 */
	protected $_fields = array(
		'user' => array(
			'id' => null,
			'salutation' => null,
			'email' => null,
			'username' => null,
			'full_name' => null,
			'first_name' => null,
			'middle_name' => null,
			'last_name' => null,
			'suffix' => null,
			'phone' => null,
		),
		'address' => array(
			'address_1' => null,
			'address_2' => null,
			'city' => null,
			'state' => null,
			'post_code' => null,
			'country_code' => null,
			'country' => null,
		),
		'item' => array(
			'name' => null,
			'description' => null,
			'selling' => 0,
			'quantity' => 0,
			'tax' => 0
		),
		'cc' => array(
			'type' => null,
			'number' => null,
			'expires' => null,
			'cvv2' => null
		),
		'order' => array(
			'custom' => null,
			'invoice_number' => null,
			'notify_url' => null,
			'currency_code' => null,
			'total' => 0,
			'shipping' => 0,
			'handling' => 0,
			'insurance' => 0,
			'tax' => 0,

			'user' => array(),
			'address' => array(),
			'items' => array(),
			'cc' => array()
		)
	);

/**
 * Constructor
 *
 * @param string $provider The provider to use
 * @param string $type the type of transaction being done
 * @param array $config configs that will be passed to the provider class
 *
 * @return void
 */
	public function __construct($provider = null) {
		if ($provider) {
			$this->provider($provider);
		}
	}

/**
 * Specify the payment provider
 *
 * @param string $provider
 * @param string $type
 *
 * @return InfinitasGateway
 */
	public function provider($provider) {
		$this->_Payment = null;
		$this->_provider = $provider;
		return $this;
	}

/**
 * Complete the order
 *
 * This finalises the details for the order and increments the orderIndex counter so the next order
 * can begin.
 *
 *
 *
 * @return InfinitasGateway
 */
	public function complete() {
		if (empty($this->_orders[$this->currentOrderindex()])) {
			return $this;
		}
		$order = $this->_orders[$this->currentOrderindex()];
		$order = array_merge($this->_fields['order'], $order);
		$order['user'] = array_merge($this->_fields('user'), $order['user']);
		$order['address'] = array_merge($this->_fields('address'), $order['address']);

		$items = $order['items'];
		$subtotal = array_sum(Hash::extract($items, '{n}.subtotal'));
		$tax = array_sum(Hash::extract($items, '{n}.tax_subtotal'));
		$total = array_sum(array(
			$subtotal,
			$tax,
			$order['shipping'],
			$order['handling'],
			$order['insurance']
		));

		$this->_orders[$this->currentOrderindex()] = $order;

		$this->_addToOrder('subtotal', $subtotal);
		$this->_addToOrder('tax', $tax);
		$this->_addToOrder('total', $total);
		$this->_orderIndex++;

		return $this;
	}

/**
 * Process a payment
 *
 * Once all the details have been added to the orders this method can be called which will dispatch
 * the details to the selected payment class for processing.
 *
 * It will return what is sent from the payment gateway class
 *
 * The returl_url and cancel_url will be caught by the InfinitasPaymentComponent no matter what plugin / controller
 * is loaded. If the plugin doing requesting the payment wishes to handle the transaction manually the
 * methods can be defined in the same controller that generates the initial request.
 *
 * @return array
 */
	public function prepare() {
		return $this->_providerClass()->sendRequest('prepare', array(
			'return_url' => InfinitasRouter::url(array(
				'action' => 'infinitas_payment_completed'
			)),
			'cancel_url' => InfinitasRouter::url(array(
				'action' => 'infinitas_payment_canceled'
			)),
			'orders' => $this->complete()->orders()
		));
	}

/**
 * Get the status of a transaction
 *
 * @param array $data
 *
 * @return array
 */
	public function status(array $data) {
		$return = $this->_providerClass()->sendRequest('status', $data);

		ClassRegistry::init('InfinitasPayments.InfinitasPaymentLog')->getTransactionDetails($return);

		return $return;
	}

/**
 * Finalise a transaction
 *
 * When a transaction is finalised the details are saved to the log
 *
 * @param array $data
 *
 * @return array
 */
	public function finalise(array $data) {
		$return = $this->_providerClass()->sendRequest('finalise', $data);

		ClassRegistry::init('InfinitasPayments.InfinitasPaymentLog')->saveTransactionDetails($return);

		return $return;
	}

/**
 * Get all the orders
 *
 * @return array
 */
	public function orders() {
		return $this->_orders;
	}

/**
 * Get the current order index
 *
 * @return integer
 */
	public function currentOrderindex() {
		return $this->_orderIndex;
	}

/**
 * Get the count of items in the order specified by the order index
 *
 * Pass null to check the current order
 *
 * @param null|integer $orderIndex the order index to check
 *
 * @return integer
 */
	public function itemCount($orderIndex = null) {
		if ($orderIndex === null) {
			$orderIndex = $this->currentOrderindex();
		}

		if (!empty($this->_orders[$orderIndex]['items'])) {
			return count($this->_orders[$orderIndex]['items']);
		}
		return 0;
	}

/**
 * Add an item to the order
 *
 * @param array $data
 *
 * @return InfinitasGateway
 */
	public function item(array $data) {
		$data = array_merge($this->_fields(__FUNCTION__), array_intersect_key($data, $this->_fields(__FUNCTION__)));
		$data['subtotal'] = $data['quantity'] * $data['selling'];
		$data['tax_subtotal'] = $data['quantity'] * $data['tax'];

		$this->_orders[$this->currentOrderindex()]['items'][] = $data;
		return $this;
	}

/**
 * Magic method for adding details to an order
 *
 * @param string $name
 * @param array $data
 *
 * @return array
 */
	public function __call($name, $data) {
		$data = current($data);
		if (stristr($name, 'url') !== false) {
			return $this->_addToOrder($name, InfinitasRouter::url($data));
		}

		if (is_array($data)) {
			$data = array_merge($this->_fields($name), $data);
			return $this->_addToOrder($name, array_intersect_key($data, $this->_fields($name)));
		}

		$floats = array(
			'shipping',
			'handling',
			'insurance',
			'subtotal',
			'tax_subtotal',
			'tax',
			'total'
		);
		if (in_array($name, $floats)) {
			return $this->_addToOrder($name, (float)$data);
		}
		return $this->_addToOrder($name, (string)$data);
	}

/**
 * Generic add data to the order
 *
 * @param string $key the key of the data to add
 * @param string|integer|float|array $data the data to add
 *
 * @return InfinitasGateway
 */
	protected function _addToOrder($key, $data) {
		$this->_orders[$this->currentOrderindex()][Inflector::underscore($key)] = $data;
		return $this;
	}

/**
 * Get the default fields for the selected type
 *
 * @param type $type
 *
 * @return array
 */
	protected function _fields($type) {
		return $this->_fields[$type];
	}

/**
 * Get the provider class
 *
 * Load up the required class and configs from the database and initialise the class. Processing only
 * occurs the first time this method is called after setting / chaning the provider
 *
 * @return PaymentSocket
 */
	protected function _providerClass() {
		if (empty($this->_Payment)) {
			if (!$this->_provider) {
				throw new InvalidProviderException();
			}

			$paymentMethod = ClassRegistry::init('InfinitasPayments.InfinitasPaymentMethod')->find('config', $this->_provider);

			$provider = Configure::read('InfinitasPayments.Provider.' . $paymentMethod['provider']);
			App::uses($provider['class'], 'InfinitasPayments.Lib/Providers/' . Inflector::classify($provider['provider']));

			$this->_Payment = new $provider['class'](array(
				'live' => $paymentMethod['live'],
				'sandbox' => $paymentMethod['sandbox'],
				'debug' => Configure::read('debug') || $paymentMethod['testing'],
			));
		}

		return $this->_Payment;
	}
}
