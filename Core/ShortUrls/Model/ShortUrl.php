<?php
/**
 * ShortUrl
 *
 * @package Infinitas.ShortUrls.Model
 */

/**
 * ShortUrl
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.ShortUrls.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ShortUrl extends ShortUrlsAppModel{
/**
 * Set up view tracking
 *
 * @var boolean
 */
	public $viewable = true;

/**
 * Disable trash behavior
 *
 * @var boolean
 */
	public $noTrash = true;

/**
 * skip confirmation
 *
 * @var bool
 */
	public $noConfirm = true;

/**
 * A string of chars used in the encoding / decoding
 *
 * @var string
 */
	private $__codeSet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

/**
 * The length of the $__codeSet
 *
 * @var integer
 */
	private $__base;

/**
 * Constructor
 *
 * @param type $id
 * @param type $table
 * @param type $ds
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		parent::__construct($id, $table, $ds);

		$this->validate = array(
			'url' => array(
				'isUnique' => array(
					'rule' => 'isUnique',
					'message' => __d('short_urls', 'That url has already been shortened')
				),
				'url' => array(
					'rule' => 'someTypeOfUrl',
					'message' => __d('short_urls', 'Please enter a valid url (something that does in href)')
				),
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => __d('short_urls', 'Please enter the url to shorten')
				)
			)
		);

		$this->__base = strlen($this->__codeSet);
	}

	/**
	 * make sure we save only valid urls.
	 *
	 * @param array the field being validated
	 *
	 * @return boolean
	 */
	public function someTypeOfUrl($field) {
		// absolute url
		if(Validation::url(current($field), true)) {
			return true;
		}

		else if(false) {
			// validate json so that you can use a cake url that can change with routing.
		}

		return false;
	}

	/**
	 * Convert a long url to a short one.
	 *
	 * @param string $url the url to shroten
	 *
	 * @return string
	 */
	public function shorten($url = null) {
		if(!$url) {
			return false;
		}

		$data['ShortUrl']['url'] = $url;
		$this->create();
		if(!$this->save($data)) {
			if(current($this->validationErrors['url']) == $this->validate['url']['isUnique']['message']) {
				$id = $this->find('first', array(
					'conditions' => array(
						'ShortUrl.url' => $url
					)
				));

				$this->id = isset($id['ShortUrl']['id']) ? $id['ShortUrl']['id'] : false;

				if(!$this->id) {
					return false;
				}
			}
		}

		return $this->__encode($this->id);
	}

	/**
	 * convert a short url to a long one.
	 *
	 * @param string $code the code from the short url
	 *
	 * @return boolean|string
	 */
	public function getUrl($code = null) {
		if(!$code) {
			return false;
		}

		$check = $this->read(null, $this->__decode($code));

		if(!empty($check)) {
			return $check['ShortUrl']['url'];
		}

		return false;
	}

	/**
	 * encode the id to a few chars
	 *
	 * @param integer $id
	 *
	 * @return string
	 */
	private function __encode($id) {
		$return = '';

		while ($id > 0) {
			$return = substr($this->__codeSet, ($id % $this->__base), 1) . $return;
			$id = floor($id / $this->__base);
		}

		return $return;
	}

	/**
	 * Decode a short url
	 *
	 * decode a short url to figure out the correct long url that will be
	 * returned.
	 *
	 * @param string $data the code from the short url
	 *
	 * @return integer
	 */
	private function __decode($data) {
		$return = '';
		$i = strlen($data);

		for ($i; $i; $i--) {
			$return += strpos($this->__codeSet, substr($data, (-1 * ($i - strlen($data))), 1)) * pow($this->__base, $i - 1);
		}

		return $return;
	}

}