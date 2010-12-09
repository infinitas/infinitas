<?php
	App::import('Libs', 'Emails.EmailSocket');

	/**
	 * @brief A pop3 driver for the email socket to recive emails without the php-imap extention
	 *
	 * This class implements the methods of the pop3 protocol using the EmailSocket
	 * class to do the communication between the servers. See the links for more
	 * information about the pop3 protocol
	 *
	 * @link http://www.networksorcery.com/enp/protocol/pop.htm
	 * @link http://tools.ietf.org/html/rfc1939
	 * @link http://techhelp.santovec.us/pop3telnet.htm
	 */
	class Pop3Socket extends EmailSocket{
		/**
		 * @brief The pop3 class constructor
		 *
		 * You can set connection details in a number of ways. First being passing
		 * an array of connection details to the constructor. The other method
		 * is using Pop3Socket::set(), finally you can set the properties through
		 * Pop3Socket::__set() by doing $Pop3Socket->{$name} = $value
		 *
		 * If parent is not called the connection to the server will not be set
		 * up automagically.
		 *
		 * @param array $connection the connection details
		 * @access public
		 *
		 * @return void
		 */
		public function  __construct($connection = array()) {
			parent::__construct($connection);
			if(!empty($connection) && is_array($connection)){
				$this->set($connection);
			}
		}

		/**
		 * @copydoc EmailSocket::login()
		 */
		public function login(){
			if(!parent::login()){
				return false;
			}

			if(!$this->write(sprintf('USER %s', $this->config['username']), 'isOk')){
				$this->error(sprintf('There seems to be a problem with the username (%s)', $this->config['username']));
				return false;
			}

			if(!$this->write(sprintf('PASS %s', $this->config['password']), 'isOk')){
				$this->error(sprintf('The password seems invalid for this user (%s)', $this->config['username']));
				return false;
			}
			
			$this->_getStats();
			$this->_getList();
			return true;
		}

		/**
		 * @copydoc EmailSocket::logout()
		 */
		public function logout(){
			if(!$this->Socket->isConnected()){
				$this->_errors[] = 'Can not logout, no connection';
				return true;
			}

			$quit = $this->write('QUIT', 'isOk');

			if(!$quit){
				$this->_errors[] = 'Could not log out';
			}

			return $quit;
		}

		/**
		 * @copydoc EmailSocket::_getStats()
		 */
		protected function _getStats(){
			$stats = $this->write('STAT', 'cleanData');
			$stats =  explode(' ', current(array_keys($stats)));
			if($stats[0] != '+OK'){
				$this->_errors[] = 'Could not get stats';
			}

			if(isset($stats[1])){
				$this->mailStats['totalCount'] = $stats[1];
			}

			if(isset($stats[2])){
				$this->mailStats['totalSize'] = $stats[2];
			}

			unset($stats);

			if($this->mailStats['totalSize'] > 0){
				$this->mailStats['totalSizeReadable'] = convert($this->mailStats['totalSize']);
			}

			return true;
		}

		/**
		 * @copydoc EmailSocket::_getList()
		 */
		protected function _getList(){
			$list = $this->write('LIST ', 'cleanData', 1024);
			
			/**
			 * @todo getting big lists causes problems
			 * 

			$mailSize = parent::_getSize($list) || parent::_isOk($list);

			if(!$mailSize){
				unset($list);
				return array();
			}

			$mailSize = is_int($mailSize) ? $mailSize : 1024;

			$list = $list . $this->write('LIST ', null, $mailSize);
			
			 */

			if(!$list || empty($list)){
				return false;
			}

			$uids = $this->_getUid();

			$this->mailList = array();
			foreach($list[current(array_keys($list))] as $item){
				$parts = explode(' ', $item);
				if(count($parts) == 2 && $parts[0] > 0 && !empty($parts[1])){
					$listItem = array(
						'id' => null,
						'size' => $parts[1],
						'sizeReadable' => convert($parts[1]),
						'uid' => isset($uids[$parts[0]]) ? $uids[$parts[0]] : null
					);
					$listItem['id'] = sha1(serialize($listItem));
					$this->mailList[$parts[0]] = $listItem;
				}
			}
			
			unset($list, $parts);
			return true;
		}

		protected function _getUid($message_id = null){
			$return = array();

			$data = $this->write('UIDL');
			
			if($this->_isOk($data)){
				$data = current($this->_cleanData($data));
				foreach($data as $_data){
					$_data = explode(' ', $_data);
					$return[$_data[0]] = $_data[1];
				}
			}
			unset($data, $_data);

			return $return;
		}

		/**
		 * @copydoc EmailSocket::_getCapabilities()
		 *
		 * This is a list of the capabilities for a pop3 mail server. This data
		 * is used to determin how the communication will be done.
		 */
		protected function _getCapabilities(){
			$cache = $this->readCache('capabilities');
			if($cache){
				$this->_capabilities = $cache;
				return true;
			}
			
			$capabilities = $this->write('CAPA', 'cleanData');
			if(empty($capabilities)){
				return false;
			}

			foreach(current($capabilities) as $capability){
				$parts = explode(' ', $capability, 2);				
				switch($capability){
					case 'TOP':			// The TOP capability indicates the optional TOP command is available.
					case 'USER':		// The USER capability indicates that the USER and PASS commands are supported, although they may not be available to all users.
					case 'SASL':		// The SASL capability indicates that the AUTH command is available and that it supports an optional base64 encoded second argument for an initial client response as described in the SASL specification.
					case 'RESP-CODES':	// any response text that begins with [ is an extended response code
					case 'PIPELINING':	// The PIPELINING capability indicates the server is capable of accepting multiple commands at a time; the client does not have to wait for the response to a command before issuing a subsequent command.
					case 'UIDL':		// The UIDL capability indicates that the optional UIDL command is supported.
					case 'STLS':
						$this->_capabilities[$parts[0]] = 1;
						break;

					case 'LOGIN-DELAY':		// time to pass before re logging on
					case 'EXPIRE':			// The argument to the EXPIRE capability indicates the minimum server retention period, in days, for messages on the server. EXPIRE 0 means you can not store on the server. EXPIRE NEVER asserts that the server does not delete messages.
					case 'IMPLEMENTATION':	// It is often useful to identify an implementation of a particular server
						$this->_capabilities[$parts[0]] = isset($parts[1]) && !empty($parts[1]) ? $parts[1] : 1;
						break;
				}
			}

			return $this->writeCache('capabilities', $this->_capabilities);
		}

		/**
		 * @copydoc EmailSocket::noop()
		 */
		public function noop(){
			return $this->write('NOOP', 'isOk');
		}

		/**
		 * @copydoc EmailSocket::undoDeletes()
		 */
		public function undoDeletes(){
			return $this->write('RSET', 'isOk');
		}

		public function getMail($id){
			$data = $this->write('RETR ' . $id, null, 50);
			$mailSize = parent::_getSize($data);
			if(!$mailSize){
				unset($data);
				return array();
			}
			
			$mail = $data . $this->write('RETR ' . $id, null, $mailSize);
			if(!parent::_isOk($mail)){
				unset($mail);
				return array();
			}

			$mail = array_merge($this->mailList[$id], $this->ParseMail->parse($mail));
			
			return $mail;
		}

		/**
		 * @copydoc EmailSocket::_getMailboxes()
		 *
		 * pop3 does not have "mailboxes" like IMAP so just return null
		 */
		protected function _getMailboxes($ref = '', $wildcard = '*'){
			return null;
		}
	}