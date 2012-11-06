<?php
App::uses('EmailSocket', 'Emails.Network');

/**
 * @brief A pop3 driver for the email socket to recive emails without the php-imap extention
 *
 * This class implements the methods of the pop3 protocol using the EmailSocket
 * class to do the communication between the servers. See the links for more
 * information about the pop3 protocol
 *
 * @link http://en.wikipedia.org/wiki/Internet_Message_Access_Protocol
 */
class ImapSocket extends EmailSocket {
/**
 * @brief counter for message sending
 *
 * All messages are given a suffix of Axxxxx where x is an int starting at 0
 *
 * @var integer
 */
	protected $_counter = 0;

/**
 * @brief current mailbox
 *
 * @var string
 */
	protected $_mailbox = null;
/**
 * @brief mailbox flags
 *
 * @var array
 */
	protected $_flags = array();

/**
 * @copydoc EmailSocket::login()
 */
	public function login() {
		if(!parent::login()) {
			return false;
		}

		if(!$this->write(sprintf('LOGIN %s %s', $this->config['username'], $this->config['password']), 'isOk')) {
			$this->error(sprintf('There seems to be a problem with the username (%s)', $this->config['username']));
			return false;
		}

		$this->_getMailboxes();
		$this->_selectMailbox();
		$this->_getStats();
		$this->_getList();
		return true;
	}

	protected function _selectMailbox($mailbox = 'INBOX') {
		$mailbox = $this->write(sprintf('SELECT %s', $mailbox), 'cleanMailbox');
		$this->_mailbox = $mailbox;
	}

	protected function _cleanMailbox($data) {
		if(empty($this->_flags[$this->_mailbox])) {
			$this->_flags[$this->_mailbox] = $this->_getFlags($data);
		}
	}

	protected function _getFlags($data) {
		preg_match_all('/([a-z]+)/i', current(explode("\n", $data, 2)), $flags);
		if(!empty($flags[1])) {
			foreach($flags[1] as $k => $flag) {
				if(in_array($flag, array('FLAGS', 'FETCH'))) {
					unset($flags[1][$k]);
				}
			}
			sort($flags[1]);
			return $flags[1];
		}
		return array();
	}

	public function getMail($id) {
		if(empty($this->_emails[$this->_mailbox][$id])) {
			return false;
		}

		$email = $this->write(sprintf('FETCH %s BODY[text]', $id), 'getMail');
		$header = $this->write(sprintf('FETCH %s BODY.PEEK[HEADER.FIELDS (SUBJECT DATE FROM TO)]', $id), 'getHeader');

		$email['headers'] = array(
			'dkim_signature' => '',
			'domainkey_signature' => '',
			'spam_status' => '',
			'spam_level' => '',
			'delivery_date' => array(
				'date_time' => '',
				'time_zone' => ''
			),
			'date' => $header['date'],
			'received' => '',
			'from' => $header['from'],
			'subject' => $header['subject'],
			'to' => $header['to']
		);
		$email['sizeReadable'] = convert($email['details']['html']['size']);

		return array_merge($this->_emails[$this->_mailbox][$id], $email);
	}

	protected function _getMail($data) {
		$id = array();
		preg_match('/--([a-f0-9]+)/', $data, $id);
		if(empty($id[1])) {
			return array();
		}

		$parts = array();
		list($id, $parts[0], $parts[1]) = explode($id[0], $data);
		preg_match('/\{([0-9]+)\}/', $id, $id);

		$return = array();
		foreach($parts as $part) {
			list($mime, $part) = explode("\n", trim($part), 2);
			$part = trim($part);

			$_mime = $_charset = array();
			preg_match('/text\/([a-z]+);/', $mime, $_mime);
			preg_match('/charset=(.*)$/', $mime, $_charset);
			$return['details'][$_mime[1]] = array(
				'content' => $part,
				'charset' => !empty($_charset[1]) ? trim($_charset[1]) : null,
				'type' => !empty($_mime[1]) ? trim($_mime[1]) : null,
				'size' => strlen($part)
			);
			$return[$_mime[1]] = $part;
		}
		return $return;
	}

	protected function _getHeader($data) {
		$data = explode("\n", $data);

		$return = array();
		foreach($data as $k => &$v) {
			$v = trim($v);
			if(empty($v) || $v == ')') {
				unset($data[$k]);
				continue;
			}

			$array = array(
				'from' => '/^From: (.*)$/',
				'date' => '/^Date: (.*)$/',
				'subject' => '/^Subject: (.*)$/',
				'to' => '/^To: (.*)$/'
			);

			foreach($array as $type => $regex) {
				$matches = array();
				if(preg_match($regex, $v, $matches)) {
					$matches[1] = trim($matches[1]);
					$return[$type] = $matches[1];

					if($type == 'date') {
						$timezone = array();
						preg_match('/(\+[0-9]+)$/', $matches[1], $timezone);
						$return[$type] = array(
							'date_time' => date('Y-m-d H:i:s', strtotime($matches[1])),
							'time_zone' => $timezone[1]
						);
					} else if($type == 'from') {
						$email = array();
						preg_match('/<(.*)>$/', $matches[1], $email);
						$return[$type] = array(
							'name' => trim(str_replace(sprintf('<%s>', $email[1]), '', $matches[1])),
							'email' => $email[1]
						);
					}
				}
			}
		}

		return array_merge(array(
			'from' => null,
			'date' => array(),
			'subject' => null,
			'to' => null
		), $return);
	}

/**
 * @copydoc EmailSocket::logout()
 */
	public function logout() {
		if(!$this->Socket->isConnected()) {
			$this->_errors[] = 'Can not logout, no connection';
			return true;
		}

		$quit = $this->write('QUIT', 'isOk');

		if(!$quit) {
			$this->_errors[] = 'Could not log out';
		}

		return $quit;
	}

	protected function _getStats() {
		foreach($this->write('UID SEARCH ALL', 'mailIds') as $k => $mailId) {
			$this->_emails[$this->_mailbox][$k+1] = array(
				'uuid' => $mailId,
				'id' => $mailId,
				'message_number' => $k + 1
			);
		}

		$this->mailStats['totalCount'] = count($this->_emails[$this->_mailbox]);
		$this->mailStats['totalSize'] = 0;

		unset($stats);

		if($this->mailStats['totalSize'] > 0) {
			$this->mailStats['totalSizeReadable'] = convert($this->mailStats['totalSize']);
		}

		return true;
	}

	protected function _mailIds($data) {
		$mailIds = array_filter(explode(' ', str_replace('* SEARCH', '', current(explode("\n", $data, 2)))));
		array_walk($mailIds, function(&$row) {
			$row = trim($row);
		});
		return $mailIds;
	}

	protected function _cleanSize($data) {
		return 0;
	}

	protected function _getList() {
		$i = 0;
		foreach($this->_emails[$this->_mailbox] as $mailId => &$mail) {
			$mail['message_number'] = $i++;
			$mail['flags'] = $this->write(sprintf('FETCH %s (flags)', $i), 'getFlags');
			$mail['id'] = $mailId;
			$mail['size'] = $this->write(sprintf('FETCH %d RFC822.SIZE', $mailId), 'cleanSize');
			$mail['sizeReadable'] = 0;
			$mail['uid'] = '';

		}
		return true;
	}

	protected function _list($data) {
		$data = explode("\n", $data);
		array_shift($data);

		foreach($data as $k => &$v) {
			$v = trim($v);
			if(empty($v) || $v == ')') {
				unset($data[$k]);
				continue;
			}

			$array = array(
				'From',
				'Date',
				'Subject',
				'To'
			);
			var_dump($v);
			exit;
		}
		var_dump($data);

		exit;
	}

	protected function _getCapabilities() {
		$cache = $this->readCache('capabilities');
		if($cache) {
			$this->_capabilities = $cache;
			return true;
		}

		$capabilities = $this->write('CAPABILITY', 'cleanCapabilities');
		if(empty($capabilities)) {
			return false;
		}
		foreach($capabilities as $capability) {
			switch($capability) {
				case 'IMAP4rev1':
				case 'UNSELECT':
				case 'IDLE':
				case 'NAMESPACE':
				case 'QUOTA':
				case 'ID':
				case 'XLIST':
				case 'X-GM-EXT-1':
				case 'XYZZY':
				case 'SASL-IR':
				case 'XOAUTH':
				case 'XOAUTH2':
					$this->_capabilities[$capability] = 1;
					break;
			}
		}

		return $this->writeCache('capabilities', $this->_capabilities);
	}

	protected function _cleanCapabilities($data) {
		$data = explode(' ', current(explode("\n", $data, 2)));
		if(empty($data)) {
			return array();
		}

		array_walk($data, function(&$cap) {
			$cap = trim($cap);
		});
		foreach($data as $k => $v) {
			if(in_array($v, array('*', 'CAPABILITY'))) {
				unset($data[$k]);
				continue;
			}
			if(strstr($v, 'AUTH=')) {
				$data[] = str_replace('AUTH=', '', $v);
				unset($data[$k]);
				continue;
			}
		}

		return $data;
	}

	public function write($data, $method = false, $size = 1024) {
		$data = sprintf('A%s %s', str_pad($this->_counter++, 4, '0', STR_PAD_LEFT), $data);
		return parent::write($data, $method, $size);
	}

/**
 * @brief get a list of available mail boxes
 *
 * @param string $ref
 * @param string $wildcard
 *
 * @return void
 */
	protected function _getMailboxes($ref = '""', $wildcard = '%') {
		$this->_mailboxes = $this->write(sprintf('LIST %s %s', $ref, $wildcard), 'cleanMailboxes');
	}

	protected function _cleanMailboxes($data) {
		$matches = array();
		preg_match_all('/"\/" "(.*)"/', $data, $matches);

		if(!empty($matches[1])) {
			return $matches[1];
		}

		return array();
	}

	public function noop() {
		throw new Exception(__FUNCTION__);
	}

	public function undoDeletes() {
		throw new Exception(__FUNCTION__);
	}

	protected function _isOk($data) {
		return (bool)preg_match('/A[0-9]+ OK /', $data);
	}

}