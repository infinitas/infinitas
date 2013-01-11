<?php
App::uses('HttpSocket', 'Network/Http');

abstract class PaymentSocket extends HttpSocket {

	public static $PENDING = 0;

	public static $PAID = 1;

	public static $CANCELED = -1;

	public static $ERROR = -2;

	protected $_config = array();

/**
 * Config options
 *
 * @var array
 */
	protected $_configDefaults = array(
		'live' => array(
			'site' => null,
			'api' => null,
			'password' => null,
			'email' => null,
			'signature' => null,
		),
		'sandbox' => array(
			'site' => null,
			'api' => null,
			'password' => null,
			'email' => null,
			'signature' => null,
		)
	);

	protected $_styles = array(
		'header' => array(
			'image' => null,
			'border_colour' => null,
			'background_colour' => null
		),
		'page' => array(
			'background_colour' => null
		)
	);

/**
 * The type of payment to make
 *
 * @var array
 */
	protected $_paymentType = null;

/**
 * Sandbox mode
 *
 * @var boolean
 */
	protected $_sandbox = false;

/**
 * Constructor
 *
 * Build the config options
 *
 * @param array $config the config options to use
 *
 * @return void
 */
	public function __construct(array $config = array()) {
		$config = array_merge(array(
			'debug' => (bool)Configure::read('debug'),
			'live' => array(),
			'sandbox' => array()
		), $config);

		$this->sandbox($config['debug']);

		$this->setConfig('live', (array)$config['live']);
		$this->setConfig('sandbox', (array)$config['sandbox']);

		parent::__construct();
	}

/**
 * Set the config options
 *
 * @param string $type the type of config being set
 * @param array $config the config options
 *
 * @return array
 *
 * @throws PaymentConfigTypeException
 */
	public function setConfig($type, array $config) {
		if (!in_array($type, array_keys($this->_configDefaults))) {
			throw new PaymentConfigTypeException(array($type));
		}

		if (empty($this->_config[$type])) {
			$this->_config[$type] = array();
		}
		$this->_config[$type] = array_merge(
			$this->_configDefaults[$type],
			$this->_config[$type],
			$config
		);

		return $this->getConfig($type);
	}

/**
 * Get the config options based on the sandbox state
 *
 * @return array
 */
	public function getConfig($type = null, $field = null) {
		if (!$type) {
			$type = $this->_sandbox ? 'sandbox' : 'live';
		}
		if (!in_array($type, array_keys($this->_configDefaults))) {
			throw new PaymentConfigTypeException(array($type));
		}
		if ($field && array_key_exists($field, $this->_config[$type])) {
			return $this->_config[$type][$field];
		}
		return $this->_config[$type];
	}

/**
 * Set the sandbox state
 *
 * Pass true to use the sandbox or false for live transactions
 *
 * @param boolean $state the sandbox state to use
 *
 * @return void
 */
	public function sandbox($state) {
		$this->_sandbox = (bool)$state;
	}

/**
 * configure the payment type
 *
 * @param string $type the payment type to use
 *
 * @return void
 */
	public function paymentType($type) {
		if ($this->_paymentMethod($type)) {
			$this->_paymentType = $type;
		}
	}

	protected function _paymentMethod($type) {
		$method = '_payment' . Inflector::camelize($type);

		if (!method_exists($this, $method)) {
			throw new PaymentInvalidRequestException($type);
		}

		return $method;
	}

	public function sendRequest($type, array $data) {
		$options = $this->{$this->_paymentMethod($type)}('before', $data);
		$response = $this->_request($options);
		return $this->{$this->_paymentMethod($type)}('after', $data, $response);
	}

	protected function _request(array $options) {
		$response = $this->post($this->getConfig(null, 'api') , $options);
		if ($response->code != 200) {
			throw new PaymentFailedResponse(array($response->code));
		}

		if (empty($response->body)) {
			throw new PaymentEmptyResponse(array());
		}

		return $response->body;
	}
}