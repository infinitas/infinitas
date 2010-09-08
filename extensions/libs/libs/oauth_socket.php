<?php
	/**
	 * Extension to CakePHP core HttpSocket class that overrides the request method
	 * and intercepts requests whose $request['auth']['method'] param is 'OAuth'.
	 *
	 * The correct OAuth Authorization header is determined from the request params
	 * and then set in the $request['header']['Authorization'] param of the request
	 * array before passing it back to HttpSocket::request() to send the request and
	 * parse the response.
	 *
	 * So to trigger OAuth, add $request['auth']['method'] = 'OAuth' to your
	 * request. In addition, you'll need to add your consumer key in the
	 * $request['auth']['oauth_consumer_key'] and your consumer secret in the
	 * $request['auth']['oauth_consumer_secret'] param. These are given to you by
	 * the OAuth provider. And once you have them, $request['auth']['oauth_token']
	 * and $request['auth']['oauth_token_secret'] params. Your OAuth provider may
	 * require you to send additional params too. Include them in the
	 * $request['auth'] array and they'll be passed on in the Authorization header
	 * and considered when signing the request.
	 *
	 * @author Neil Crookes <neil@neilcrookes.com>
	 * @link http://www.neilcrookes.com
	 * @copyright (c) 2010 Neil Crookes
	 * @license MIT License - http://www.opensource.org/licenses/mit-license.php
	 */

	App::import('Core', 'HttpSocket');
	class OauthSocket extends HttpSocket {

		/**
		 * Default OAuth parameters. These get merged into the $request['auth'] param.
		 *
		 * @var array
		 */
		var $defaults = array(
				'oauth_version' => '1.0',
				'oauth_signature_method' => 'HMAC-SHA1',
		);

		/**
		 * Overrides HttpSocket::request() to handle cases where
		 * $request['auth']['method'] is 'OAuth'.
		 *
		 * @param array $request As required by HttpSocket::request(). NOTE ONLY
		 *   THE ARRAY TYPE OF REQUEST IS SUPPORTED
		 * @return array
		 */
		function request($request = array()) {

			// If the request does not need OAuth Authorization header, let the parent
			// deal with it.
			if (!isset($request['auth']['method']) || $request['auth']['method'] != 'OAuth') {
				return parent::request($request);
			}

			// Generate the OAuth Authorization Header content for this request from the
			// request data and add it into the request's Authorization Header. Note, we
			// don't just add the header directly in the request variable and return the
			// whole thing from the authorizationHeader() method because in some cases
			// we may not want the authorization header content in the request's
			// authorization header, for example, OAuth Echo as used by Twitpic and
			// Twitter includes an Authorization Header as required by twitter's verify
			// credentials API in the X-Verify-Credentials-Authorization header.
			$request['header']['Authorization'] = $this->authorizationHeader($request);

			// Now the Authorization header is built, fire the request off to the parent
			// HttpSocket class request method that we intercepted earlier.
			return parent::request($request);

		}

		/**
		 * Returns the OAuth Authorization Header string for a given request array.
		 *
		 * This method is called by request but can also be called directly, which is
		 * useful if you need to get the OAuth Authorization Header string, such as
		 * when integrating with a service that uses OAuth Echo (Authorization
		 * Delegation) e.g. Twitpic. In this case you send a normal unauthenticated
		 * request to the service e.g. Twitpic along with 2 extra headers:
		 * - X-Auth-Service-Provider - effectively, this is the realm that identity
		 *   delegation should be sent to - in the case of Twitter, just set this to
		 *   https://api.twitter.com/1/account/verify_credentials.json;
		 * - X-Verify-Credentials-Authorization - Consumer should create all the OAuth
		 *   parameters necessary so it could call
		 *   https://api.twitter.com/1/account/verify_credentials.json using OAuth in
		 *   the HTTP header (e.g. it should look like OAuth oauth_consumer_key="...",
		 *   oauth_token="...", oauth_signature_method="...", oauth_signature="...",
		 *   oauth_timestamp="...", oauth_nonce="...", oauth_version="...".
		 *
		 * @param array $request As required by HttpSocket::request(). NOTE ONLY
		 *   THE ARRAY TYPE OF REQUEST IS SUPPORTED
		 * @return String
		 */
		function authorizationHeader($request) {

			$request['auth'] = array_merge($this->defaults, $request['auth']);

			// Nonce, or number used once is used to distinguish between different
			// requests to the OAuth provider
			if (!isset($request['auth']['oauth_nonce'])) {
				$request['auth']['oauth_nonce'] = md5(uniqid(rand(), true));
			}

			if (!isset($request['auth']['oauth_timestamp'])) {
				$request['auth']['oauth_timestamp'] = time();
			}

			// Now starts the process of signing the request. The signature is a hash of
			// a signature base string with the secret keys. The signature base string
			// is made up of the request http verb, the request uri and the request
			// params, and the secret keys are the consumer secret (for your
			// application) and the access token secret generated for the user by the
			// provider, e.g. twitter, when the user authorizes your app to access their
			// details.

			// Building the request uri, note we don't include the query string or
			// fragment. Standard ports must not be included but non standard ones must.
			$uriFormat = '%scheme://%host';
			if (isset($request['uri']['port']) && !in_array($request['uri']['port'], array(80, 443))) {
				$uriFormat .= ':' . $request['uri']['port'];
			}
			$uriFormat .= '/%path';
			$requestUrl = $this->_buildUri($request['uri'], $uriFormat);

			// OAuth reference states that the request params, i.e. oauth_ params, body
			// params and query string params need to be normalised, i.e. combined in a
			// single string, separated by '&' in the format name=value. But they also
			// need to be sorted by key, then by value. You can't just merge the auth,
			// body and query arrays together then do a ksort because there may be
			// parameters with the same name. Instead we've got to get them into an
			// array of array('name' => '<name>', 'value' => '<value>') elements, then
			// sort those elements.

			// Let's start with the auth params - however, we shouldn't include the auth
			// method (OAuth), and OAuth reference says not to include the realm or the
			// consumer or token secrets
			$requestParams = $this->assocToNumericNameValue(array_diff_key(
					$request['auth'],
					array_flip(array('realm', 'method', 'oauth_consumer_secret', 'oauth_token_secret'))
			));

			// Next add the body params if there are any and the content type header is
			// not set, or it's application/x-www-form-urlencoded
			if (isset($request['body']) && (!isset($request['header']['Content-Type']) || stristr($request['header']['Content-Type'], 'application/x-www-form-urlencoded'))) {
				$requestParams = array_merge($requestParams, $this->assocToNumericNameValue($request['body']));
			}

			// Finally the query params
			if (isset($request['uri']['query'])) {
				$requestParams = array_merge($requestParams, $this->assocToNumericNameValue($request['uri']['query']));
			}

			// Now we can sort them by name then value
			usort($requestParams, array($this, 'sortByNameThenByValue'));

			// Now we concatenate them together in name=value pairs separated by &
			$normalisedRequestParams = '';
			foreach ($requestParams as $k => $requestParam) {
				if ($k) {
					$normalisedRequestParams .= '&';
				}
				$normalisedRequestParams .= $requestParam['name'] . '=' . $this->parameterEncode($requestParam['value']);
			}

			// The signature base string consists of the request method (uppercased) and
			// concatenated with the request URL and normalised request parameters
			// string, both encoded, and separated by &
			$signatureBaseString = strtoupper($request['method']) . '&'
					. $this->parameterEncode($requestUrl) . '&'
					. $this->parameterEncode($normalisedRequestParams);

			// The signature base string is hashed with a key which is the consumer
			// secret (assigned to your application by the provider) and the token
			// secret (also known as the access token secret, if you've got it yet),
			// both encoded and separated by an &
			$key = '';
			if (isset($request['auth']['oauth_consumer_secret'])) {
				$key .= $this->parameterEncode($request['auth']['oauth_consumer_secret']);
			}
			$key .= '&';
			if (isset($request['auth']['oauth_token_secret'])) {
				$key .= $this->parameterEncode($request['auth']['oauth_token_secret']);
			}

			// Finally construct the signature according to the value of the
			// oauth_signature_method auth param in the request array.
			switch ($request['auth']['oauth_signature_method']) {
				case 'HMAC-SHA1':
					$request['auth']['oauth_signature'] = base64_encode(hash_hmac('sha1', $signatureBaseString, $key, true));
					break;
				default:
				// @todo implement the other 2 hashing methods
					break;
			}

			// Finally, we have all the Authorization header parameters so we can build
			// the header string.
			$authorizationHeader = 'OAuth';

			// We don't want to include the realm, method or secrets though
			$authorizationHeaderParams = array_diff_key(
					$request['auth'],
					array_flip(array('method', 'oauth_consumer_secret', 'oauth_token_secret', 'realm'))
			);

			// Add the Authorization header params to the Authorization header string,
			// properly encoded.
			$first = true;

			if (isset($request['auth']['realm'])) {
				$authorizationHeader .= ' realm="' . $request['auth']['realm'] . '"';
				$first = false;
			}

			foreach ($authorizationHeaderParams as $name => $value) {
				if (!$first) {
					$authorizationHeader .= ',';
				} else {
					$authorizationHeader .= ' ';
					$first = false;
				}
				$authorizationHeader .= $this->authorizationHeaderParamEncode($name, $value);
			}

			return $authorizationHeader;

		}

		/**
		 * Builds an Authorization header param string from the supplied name and
		 * value. See below for example:
		 *
		 * @param string $name E.g. 'oauth_signature_method'
		 * @param string $value E.g. 'HMAC-SHA1'
		 * @return string E.g. 'oauth_signature_method="HMAC-SHA1"'
		 */
		function authorizationHeaderParamEncode($name, $value) {
			return $this->parameterEncode($name) . '="' . $this->parameterEncode($value) . '"';
		}

		/**
		 * Converts an associative array of name => value pairs to a numerically
		 * indexed array of array('name' => '<name>', 'value' => '<value>') elements.
		 *
		 * @param array $array Associative array
		 * @return array
		 */
		function assocToNumericNameValue($array) {
			$return = array();
			foreach ($array as $name => $value) {
				$return[] = array(
						'name' => $name,
						'value' => $value,
				);
			}
			return $return;
		}

		/**
		 * User defined function to lexically sort an array of
		 * array('name' => '<name>', 'value' => '<value>') elements by the value of
		 * the name key, and if they're the same, then by the value of the value key.
		 *
		 * @param array $a Array with key for 'name' and one for 'value'
		 * @param array $b Array with key for 'name' and one for 'value'
		 * @return integer 1, 0 or -1 depending on whether a greater than b, less than
		 *  or the same.
		 */
		function sortByNameThenByValue($a, $b) {
			if ($a['name'] == $b['name']) {
				if ($a['value'] == $b['value']) {
					return 0;
				}
				return ($a['value'] > $b['value']) ? 1 : -1;
			}
			return ($a['name'] > $b['name']) ? 1 : -1;
		}

		/**
		 * Encodes paramters as per the OAuth spec by utf 8 encoding the param (if it
		 * is not already utf 8 encoded) and then percent encoding it according to
		 * RFC3986
		 *
		 * @param string $param
		 * @return string
		 */
		function parameterEncode($param) {
			$encoding = mb_detect_encoding($param);
			if ($encoding != 'UTF-8') {
				$param = mb_convert_encoding($param, 'UTF-8', $encoding);
			}
			$param = rawurlencode($param);
			$param = str_replace('%7E', '~', $param);
			return $param;
		}
	}