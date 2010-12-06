<?php
	class BaseSocket extends Object{
		public $buffer;

		/**
		 * @brief blank details for a connection
		 * 
		 * @var <type>
		 */
		protected $_defaults = array(
			'host' => null,
			'port' => null
		);

		protected $_uuid;
		
		protected $_socket;

		protected $_connection;

		protected $_address;

		private $__isConnected = false;

		private $__possition = 0;

		private $__errors = array();

		private $__logs = array();

		/**
		 * @brief the config options for the connection
		 *
		 * @li blocking mode
		 *	@li 0 - non-blocking mode will get data without wating till the end
		 *	@li 1 - blocking mode will wait untill all data is available before returning
		 *
		 * @li socket params
		 *	@li @see http://php.net/manual/en/function.socket-create.php
		 *
		 * @property __blocking
		 * @access private
		 */
		private $__config = array(
			'blocking' => 0,
			'socket' => array(
				'domain'   => 2, // AF_INET
				'type'     => 1, // SOCK_STREAM
				'protocol' => 6, // SOL_TCP
			),
			'timeout' => 10,
			'eof' => ".\r\n"
		);

		public function  __construct() {
			$this->_uuid = uniqid('socket_');
		}

		/**
		 * @brief open a connection to the socket
		 *
		 * @access public
		 *
		 * @return bool true if the connection was established, false if not.
		 */
		public function open($connection = array()){
			$connection = array_merge(
				$this->_defaults,
				$connection
			);

			$this->_socket = stream_socket_client(sprintf('tcp://%s:%d', $connection['host'], $connection['port']), $errorNumber, $errorString, 30);

			if($errorNumber || $errorString){
				$this->error(sprintf('%d :: %s', $errorNumber, $errorString));
				return false;
			}

			if(!$this->_socket){
				$this->error('Could not open a socket');
				return false;
			}
			
			stream_set_blocking($this->_socket, $this->__config['blocking']);

			$this->__isConnected = true;
			return true;
		}

		/**
		 * @brief close a connection to the server
		 *
		 * Will attempt to close the connection and return true if the connection
		 * was closed. If it was not able to close it will return false
		 *
		 * @access public
		 *
		 * @return bool was closed or not
		 */
		public function close(){
			if($this->_connection){
				fclose($this->_connection);
			}

			$this->_connection = null;
			$this->_uuid = null;
			$this->_config = array();
			$this->_isConnected = false;
		}

		/**
		 * @brief Get the responce from a request
		 *
		 * @return <type>
		 */
		public function read($size = 128){
			$this->buffer = $time = '';
			while(1){
				$this->buffer .= $check = fgets($this->_socket, 500);
				if($check != ''){
					$time = 0;
				}
				unset($check);

				if(substr($this->buffer, 0, 2)){
					break;
				}

				usleep(100000); // give the mail server some time to get the info

				if($time > $this->__config['timeout']){
					$this->error('No response');
					break;
				}
				$time++;
			}

			fflush($this->_socket);

			return (bool)$this->buffer;
		}

		/**
		 * @brief send a bit of data to the server
		 *
		 * This is the low level method for sending data to the server. All the
		 * requests for logins, mails etc go through here.
		 *
		 * @param string $data the command to send to the server
		 * @access private
		 *
		 * @return bool false on error, true if worked
		 */
		public function write($data, $size = null){
			if(!$this->isConnected()){
				$this->error('No connection available');
				return false;
			}

			if(!is_int($size)){
				$size = strlen($data);
			}

			$this->log($data, $size);

			return fwrite($this->_socket, $data, $size);;
		}

		public function isConnected(){
			return $this->__isConnected;
		}

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

		public function __set($name, $value = null){
			switch($name){
				case 'blocking':
					$this->__config['blocking'] = (bool)$value;
					break;

				case 'socket':
					$this->__config['socket'] = array_merge($this->__config['socket'], (array)$value);
					break;
			}
		}

		public function __get($name){
			if(isset($this->__config[$name])){
				return $this->__config[$name];
			}

			return false;
		}
	}