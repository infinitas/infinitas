<?php
class PayPalHelper extends AppHelper {

	public static $name = 'PayPal';

	public $helpers = array(
		'Form',
		'Html'
	);

	public function image(array $options = array()) {

	}

	public function link($text = null, array $options = array()) {
		if (!$text) {
			$text = self::$name;
		}
	}

	public function inputs(array $data) {

	}

	public function submit($text = null) {
		if (!$text) {
			$text = self::$name;
		}
	}
}