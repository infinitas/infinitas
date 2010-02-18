<?php
	/**
	 *
	 * vCard Helper
	 *
	 * Helps with the creation of vCard files
	 */
	class VcfHelper extends AppHelper {
		/**
		 * Element map
		 * map element names to vcard elements
		 *
		 * @var array
		 */
		protected $_elements = array(
			'name' => 'N:%last%;%first%;%middle%;%title%',
			'fullName' => 'FN:%value%',
			'organization' => 'ORG:%value%',
			'title' => 'TITLE:%value%',
			'workPhone' => 'TEL;WORK:%value%',
			'homePhone' => 'TEL;HOME:%value%',
			'cellPhone' => 'TEL;CELL:%value%',
			'address' => 'ADR',
			'birthday' => 'BDAY:%value%',
			'email' => 'EMAIL;INTERNET:%value%',
			'timezone' => 'TZ:%value%',
			'url' => 'URL:%value%',
			'version' => 'VERSION:%value%',
			'image' => 'PHOTO;VALUE=URL:%value%',
		);
		/**
		 * Separator between values.
		 *
		 * @var string
		 **/
		protected $_separator = ':';
		/**
		 * End of line character
		 *
		 * @var string
		 **/
		protected $_eol = "\n";
		/**
		 * End of attribute terminator.
		 *
		 * @var string
		 **/
		protected $_terminator = ';';
		/**
		 * Overloaded call method
		 *
		 * @param string $method Name of method called
		 * @param mixed $params Params for method.
		 * @return mixed
		 **/
		public function __call($method, $params) {
			if (isset($this->_elements[$method])) {
				array_unshift($params, $method);
				return $this->dispatchMethod('attr', $params);
			}
			trigger_error($method . ' is not a valid element.', E_USER_WARNING);
		}
		/**
		 * begin a vcard
		 *
		 * @return string
		 */
		public function begin() {
			return "BEGIN:VCARD" . $this->_eol;
		}
		/**
		 * End a vcard
		 *
		 * @return string
		 */
		public function end() {
			return "END:VCARD" . $this->_eol;
		}
		/**
		 * Create a new attribute for the vCard
		 *
		 * @param string $type Type of element to make
		 * @param string $value Value to put into the card
		 * @return mixed False on non-existant type or empty values,  string on success
		 */
		public function attr($type, $values) {
			if (empty($values)) {
				return false;
			}
			if (is_string($values)) {
				$values = array('value' => $values);
			}

			if ($type != 'image') {
				$values = $this->_escape($values);
			}

			if (!isset($this->_elements[$type])) {
				return false;
			}
			$out = String::insert($this->_elements[$type],
				$values, array('clean' => true, 'before' => '%', 'after' => '%')
			);
			return $out . $this->_eol;
		}

		/**
		 * Create an Address element. Takes the following keys
		 *
		 * - street
		 * - city
		 * - province
		 * - postal
		 * - country
		 *
		 * @param string $type The type of address you are making
		 * @param array $values Array of values for the address see above
		 */
		public function address($type, $values = array()) {
			$empty = array(
				'street' => '',
				'city' => '',
				'province' => '',
				'postal' => '',
				'country' => '',
			);
			$values = array_merge($empty, $values);
			$values['key'] = $this->_elements['address'];
			$values['type'] = strtoupper($type);

			$format = "%key%;%type%:;;%street%;%city%;%province%;%postal%;%country%;";
			$adrEl = String::insert($format, $values, array('before' => '%', 'after' => '%', 'clean' => true));
			$labelFormat = "LABEL;POSTAL;%type%;ENCODING=QUOTED-PRINTABLE:%street%=0D=0A%city%, %province% %postal%=0D=0A%country%";
			$labelEl = String::insert($labelFormat, $values, array('before' => '%', 'after' => '%', 'clean' => true));

			return $adrEl . $this->_eol . $labelEl . $this->_eol;
		}
		/**
		 * Escape values for vcard
		 *
		 * @param mixed $values Values either string or array.
		 * @return string Escaped string
		 **/
		protected function _escape($values) {
			$find = array(':');
			$replace = array('\:');
			return is_array($values) ? array_map(array($this, '_escape'), $values) : str_replace($find, $replace, $values);
		}

	}
?>