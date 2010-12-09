<?php
	//App::import('Libs', 'Libs.BaseSocket');
	App::import('CakeSocket');
	/**
	 * @base Email socket is a low level class for communicating directly with mail servers
	 *
	 * This class will connect and handle all data communications to the mail server
	 * via a "driver". The common drivers are pop3, IMAP and SMTP.
	 *
	 * @code
	 *	// example ways to connect to a server (using pop3, all drivers are the same)
	 *	App::import('Libs', 'Emails.Pop3'); // use pop3
	 *	$connection = array(
	 *		'host' => 'mail.server.com',
	 *		'username' => 'user@server.com',
	 *		'password' => 'password',
	 *		'port' => 110,
	 *		'ssl' => false,
	 *		'timeout' => 30,
	 *		'connection' => 'pop3'
	 *	);
	 *
	 *	// connect #1
	 *	$EmailSocket = new Pop3Socket($connection);
	 *	$EmailSocket->login();
	 *
	 *	// connect #2
	 *	$EmailSocket = new Pop3Socket();
	 *	$EmailSocket->set($connection)->login();
	 *
	 *	// connect #3
	 *	$EmailSocket = new Pop3Socket();
	 *	$EmailSocket->set($connection);
	 *	$EmailSocket->login();
	 *
	 *	// connect #3
	 *	$EmailSocket = new Pop3Socket();
	 *	$EmailSocket->set('host', $connection['host']);
	 *	...
	 *	$EmailSocket->login();
	 *
	 *	// connect #4
	 *	$EmailSocket = new Pop3Socket();
	 *	$EmailSocket->host = $connection['host'];
	 *	...
	 *	$EmailSocket->login();
	 * @endcode
	 */
	
	abstract class EmailSocket extends CakeSocket{
		public $ParseMail;
		
		public $config;

		public $eol;

		public $mailStats = array(
			'totalCount' => 0,
			'totalSize' => 0,
			'totalSizeReadable' => 0
		);

		public $mailList = array();

		public $description = 'Email Socket Interface';

		private $__connectionOptions = array(
			'type' => 'imap',
			'host' => '',
			'username' => '',
			'password' => '',
			'port' => '',
			'ssl' => true,
			'request' => '',
			'timeout' => 30,
			'mail_box' => ''
		);

		/**
		 * @brief a unique key per server so that some commands do not need to be run all the time
		 */
		private $__cacheKey;

		private $__errors = array();

		private $__logs = array();

		protected $_response = array();

		/**
		 * @brief a list of capabilities that the server has available.
		 *
		 * @property _capabilities
		 */
		protected $_capabilities = array();

		protected $_mailboxes = array();

		public function __construct($connection = array()){
			parent::__construct($connection);
			App::import('Libs', 'Emails.ParseMail');
			$this->ParseMail = new ParseMail();
			$this->eol = "\r\n";
		}

		/**
		 * @brief allow method chaning for setting values
		 *
		 * The set method is able to take an array of values or normal name, value
		 * params so you are able to connect in a number of ways. The following
		 * things all do the same thing.
		 *
		 * @li $Imap->set(array('name' => 'value', ....));
		 * @li $Imap->set('name', 'value')->set('name2' => 'value2')
		 * @li $Imap->name = 'value'
		 *
		 * If you are passing an array of options there the $value param is not used
		 * 
		 * @param <type> $name
		 * @param <type> $value
		 * @return ImapSocket
		 */
		public function set($name, $value = false){
			if(!is_array($name)){
				$this->{$name} = $value;
			}
			else{
				foreach($name as $key => $value){
					$this->set($key, $value);
				}
			}
			
			return $this;
		}

		/**
		 * @brief Check if there is a connection to the mail server
		 *
		 * @access public
		 *
		 * @return bool true if connected, false if not
		 */
		public function isConnected(){
			return $this->connected;
		}

		/**
		 * @brief set up the connection for the socket
		 *
		 * This method will connect to the server and wait for the welcome
		 * message. It is the responsibility of the driver to authenticate
		 * if that is needed.
		 * 
		 * @return bool was the login correct of not
		 */
		public function login(){
			parent::connect();

			if(!parent::read(1024, 'isOk')){
				$this->error('Server not responding, exiting');
				return false;
			}

			if(!$this->_getCapabilities()){
				$this->error('Unable to get the sockets capabilities');				
			}
			
			return true;
		}

		/**
		 * @brief Read data directly from the socket that is open
		 *
		 * This is the low level interface to the socket class for reading data
		 * from the mail server. You are able to pass a call back method that
		 * the data will be sent to for formatting.
		 *
		 * If there are problems reading from the mail server bool false will be
		 * returned. Other returns will be raw text or the callback method that
		 * is set.
		 *
		 * It should be the duty of the callback method to make sure it has all the
		 * data that is required and request more using EmailSocket::read() if
		 * it needs more.
		 *
		 * @param int $size the size of data to read upto
		 * @param string $method a callback method to trigger
		 * @access public
		 *
		 * @return bool|string|array false on fail, string for raw and
		 * string|array depending on the callbacks
		 */
		public function read($size = 1024, $method = false){
			$data = parent::read($size);
			if($data){
				if($method && is_callable(array($this, $method))){
					$method = '_' . $method;
					return $this->{$method}($data);					
				}

				return $data;
			}

			$this->error('Stream timed out, no data');
			return false;
		}

		public function write($data, $method = false, $size = 1024){
			$didWrite = parent::write($data . $this->eol);
			
			if($didWrite && $size > 0){
				if($method && is_callable(array($this, '_' . $method))){
					$data = parent::read($size, $method);
					$method = '_' . $method;
					return $this->{$method}($data);
				}
				
				return parent::read($size);
			}

			return $didWrite;
		}

		protected function _getSize($data){
			if(!$this->_isOk($data)){
				return 0;
			}
			/*if(strstr($data, ' messages ')){
				$data = explode('(', $data, 2);
				$data = explode(' ', isset($data[1]) ? $data[1] : '');
				return isset($data[0]) ? $data[0] : false;
			}*/
			$data = explode(' ', $data, 3);
			
			return isset($data[1]) && (int)$data[1] > 0 ? $data[1] : 0;
		}

		protected function _isOk($data){
			return substr($data, 1, 2) == 'OK';
		}

		/**
		 * @brief get the last response from the server
		 * 
		 * @return <type>
		 */
		public function lastResponse(){
			$index = count($this->_response) - 1;
			if($index < 0 || !isset($this->_response[$index])){
				return false;
			}
			
			return $this->_response[$index];
		}

		/**
		 * @brief Check if the server has the capability passed in
		 *
		 * @param string $capability check the drivers for a list of capabilities
		 * 
		 * @return bool it does or does not
		 */
		public function hasCapability($capability){
			return isset($this->_capabilities[$capability]);
		}

		/**
		 * @brief Explode the data into an array and remove the new lines
		 *
		 * the data will be returned as array('response_code' => array('data', 'goes', 'here'))
		 *
		 * @param string $data the data from the read
		 * @return array formatted data without the response
		 */
		public function _cleanData($data){
			if($data === false || empty($data)){
				return false;
			}
			
			$list = array_filter(explode("\r\n", str_replace('.', '', $data)));
			unset($data);
			
			$response = array_shift($list);

			return array($response => $list);
		}

		/**
		 * @brief Generate a unique cache key for the server that is being used.
		 */
		private function __cacheKey(){
			if(!$this->__cacheKey){
				$this->__cacheKey = sha1(serialize(array($this->config['host'], $this->config['type'], $this->config['port'], $this->config['ssl'])));
			}

			return $this->__cacheKey;
		}

		/**
		 * @brief wrapper for Cache::read()
		 *
		 * @link http://api13.cakephp.org/class/cache#method-Cacheread
		 * 
		 * @param string $key the name of the cache file
		 * @param string $config the config to use when reading
		 * @access public
		 * 
		 * @return mixed false for nothing, any data if there was
		 */
		public function readCache($key, $config = 'emails'){
			return Cache::read($this->__cacheKey() . '.' . $key, $config);
		}

		/**
		 * @brief wrapper for Cache::write()
		 *
		 * @link http://api13.cakephp.org/class/cache#method-Cachewrite
		 *
		 * @param string $key the name of the cache file
		 * @param array $data the data that should be written to cache
		 * @param string $config the config to use when writing
		 * @access public
		 *
		 * @return bool true on write, false for error.
		 */
		public function writeCache($key, $data, $config = 'emails'){
			return Cache::write($this->__cacheKey() . '.' . $key, $data, $config);
		}

		/**
		 * @brief attempt to log the user out of the mail server
		 *
		 * This is part of the cleanup process, if the driver has done authentication
		 * you should use this method to logout of the session.
		 *
		 * @return <type>
		 */
		abstract public function logout();

		/**
		 * @brief Get some basic stats for the email account
		 *
		 * This method should be cached per user and can be used as a way to
		 * check if there is any new mail. A hash of the number of mails + the
		 * total size could be used.
		 *
		 * @return bool return true, or false on error
		 */
		abstract protected function _getStats();

		/**
		 * @brief Get a list of the mail numbers and the sizes for later use
		 *
		 * @return bool true if found, false if not
		 */
		abstract protected function _getList();

		/**
		 * @brief Get the details of what the server has available
		 */
		abstract protected function _getCapabilities();

		/**
		 * @brief Get a list of possible mail boxes
		 *
		 * This should be cached per user
		 *
		 * @access abstract
		 *
		 * @return bool|array false on error, or array of mailboxes
		 */
		abstract protected function _getMailboxes($ref = '', $wildcard = '*');

		/**
		 * @brief Just returns a +OK response for keep-alive
		 *
		 * @link http://www.apps.ietf.org/rfc/rfc1939.html#page-9
		 *
		 * @access public
		 *
		 * @return bool true if server is still available, false when connection is lost
		 */
		abstract public function noop();

		/**
		 * @brief Sort of undelete
		 *
		 * will reset mails that were set to delete.
		 *
		 * @link http://www.apps.ietf.org/rfc/rfc1939.html#page-9
		 *
		 * @access public
		 *
		 * @return bool
		 */
		abstract public function undoDeletes();
		/**
		 * @brief record an error
		 *
		 * @param string $text the error message
		 * @access public
		 *
		 * @return bool true
		 */
		public function error($text){
			$this->__errors[] = $text;
			return true;
		}

		/**
		 * @brief Log the raw data that the server is returning
		 *
		 * @param string $text the raw logs
		 * @param integer $size the size of the packet
		 * @access public
		 *
		 * @return bool true
		 */
		public function log($text, $size = 0){
			$this->__logs[] = array(
				'data' => substr($text, 0, -2),
				'size' => $size ? $size : strlen($text)
			);

			unset($text, $size);
			return true;
		}

		/**
		 * @brief Get any errors that occured during the connection
		 *
		 * @access public
		 *
		 * @return array the errors
		 */
		public function errors(){
			if(empty($this->__errors)){
				return false;
			}

			return $this->__errors;
		}

		/**
		 * @brief public method to get the raw logs
		 *
		 * @access public
		 *
		 * @return array the logs
		 */
		public function logs(){
			if(empty($this->__logs)){
				return false;
			}

			return $this->__logs;
		}

		/**
		 * @brief set the connection details
		 *
		 * @param string $name
		 * @param string|integer $value
		 * @access public
		 *
		 * @return ImapInterface
		 */
		public function __set($name, $value = null){			
			if(!in_array($name, $this->__connectionOptions)){
				$this->_errors[] = sprintf('You may not set the %s property', $name);
				return false;
			}
			
			switch($name){
				case 'port':
					if((int)$value == 0){
						$this->_errors[] = sprintf('The port "%s" is not valid', $value);
						return false;
					}
					break;

				case 'ssl':
					if($value){
						$this->request = array('uri' => array('scheme' => 'https'));
						return;
					}
					break;

				case 'host':
				case 'username':
					if(!is_string($value)){
						$this->_errors[] = sprintf('%s should be a string, "%s" is not valid', $name, gettype($value));
						return false;
					}
					if(empty($value)){
						$this->_errors[] = '%s should not be empty';
						return false;
					}
					break;
			}

			$this->config[$name] = $value;
		}
	}