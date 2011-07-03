<?php
	/**
	 * Google Api Base Class
	 *
	 * Methods used to interact with Google Api
	 *
	 * Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @copyright Copyright (c) 2009 Juan Carlos del Valle ( imekinox )
	 * @link http://www.imekinox.com
	 * @package google
	 * @subpackage google.vendors.GoogleApiBase
	 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
	 */
	App::import('Core', 'HttpSocket');
	App::import('Core', 'Session');
	App::import('Xml');

	/**
	 * GoogleApiBase
	 *
	 * Datasource for Google API
	 */
	class GoogleApiBase {
		/**
		 * Version for this Data Source.
		 *
		 * @var string
		 * @access public
		 */
		public $version = '0.1';

		/**
		 * Description string for this Data Source.
		 *
		 * @var string
		 * @access public
		 */
		public $description = 'Google Base Datasource';

		/**
		 * Client Login URL
		 *
		 * @var string
		 * @access protected
		 */
		protected $_loginUri = "https://www.google.com/accounts/ClientLogin"; //you'll have to uncomment extension=php_openssl.dll from php.ini

		/**
		 * Auth key returned by google API
		 *
		 * @var string
		 * @access protected
		 */
		protected $_authKey;

		/**
		 * Method used to make requests (curl or file_get_contents)
		 *
		 * @var string
		 * @access protected
		 */
		protected $_method;

		/**
		 * Default Constructor
		 *
		 * @param array $config options
		 * @access public
		 */
		public function __construct($config) {
			// _toPost keys are case sensitive for google api, changin them will result in bad authentication
			$_toPost['accountType'] = $config['accounttype'];
			$_toPost['Email'] = $config['email'];
			$_toPost['Passwd'] = $config['passwd'];
			$_toPost['service'] = $config['service'];
			$_toPost['source'] = $config['source'];

			$this->HttpSocket = new HttpSocket();
			// Initializing Cake Session
			$session = new CakeSession();
			$session->start();

			// Validating if curl is available
			if (function_exists('curl_init')) {
				$this->_method = 'curl';
			}

			else {
				$this->_method = 'fopen';
			}

			// Looking for auth key in cookie of google api client login
			$cookieKey = $session->read('GoogleClientLogin' . $_toPost['service'] . '._authKey');
			if ($cookieKey == null || $cookieKey == '') {
				// Geting auth key via HttpSocket
				$results = $this->HttpSocket->post($this->_loginUri, $_toPost);
				$firstSplit = split("\n", $results);
				foreach ($firstSplit as $string) {
					$arr = split('=', $string);

					if ($arr[0] == 'Auth'){
						$this->_authKey = $arr[1];
					}
				}

				$session->write('GoogleClientLogin' . $_toPost['service'] . '._authKey', $this->_authKey);
			}

			else {
				$this->_authKey = $cookieKey;
			}
		}

		/**
		 * Send Request
		 *
		 * @param string $url URL to do the request
		 * @param string $method GET or POST
		 * @return xml object
		 * @access public
		 */
		public function sendRequest($url, $action, $content = null) {
			/*
			  Could'nt find a way to do it via HttpSocket i got empty result

			  $auth['header'] = "Authorization: GoogleLogin auth=" . $this->_authKey;
			  $result = $HttpSocket->get("http://www.google.com/m8/feeds/contacts/jc.ekinox@gmail.com/full", array(), $auth);
			 */
			$header[] = "Authorization: GoogleLogin auth=" . $this->_authKey;
			$header[] = "GData-Version: 3.0";
			
			switch ($action) {
				case "CREATE":
					$method = "POST";
					$header[] = "Content-type: application/atom+xml";
					// $header[] = "Content-length: 1";
					$header[] = "If-Match: *";
					break;

				case "READ":
					$method = "GET";
					break;

				case "UPDATE":
					$header[] = "Content-type: application/atom+xml";
					$header[] = "X-HTTP-Method-Override: PUT";
					$header[] = "If-Match: *";
					$method = "POST";
					break;

				case "DELETE":
					break;
			}

			if ($this->_method == 'curl') {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

				if ($action == "UPDATE") {
					curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
				}

				$atom = curl_exec($ch);
				curl_close($ch);
			}

			else {
				$opts = array(
					'http' => array(
						'method' => $method,
						'header' => implode("\r\n", $header)
					)
				);
				$context = stream_context_create($opts);

				if ($action == "UPDATE") {
					$atom = file_put_contents($url, $content, null, $context);
				}

				else {
					$atom = file_get_contents($url, false, $context);
				}
			}

			debug("GOOGLE RESPONSE: " . $atom);			
			return Set::reverse(new XML($atom));
		}
	}