<?php
App::uses('EmailSocket', 'Emails.Network');

/**
 * A pop3 driver for the email socket to recive emails without the php-imap extention
 *
 * This class implements the methods of the pop3 protocol using the EmailSocket
 * class to do the communication between the servers. See the links for more
 * information about the pop3 protocol
 *
 * @link http://www.networksorcery.com/enp/protocol/pop.htm
 * @link http://tools.ietf.org/html/rfc1939
 * @link http://techhelp.santovec.us/pop3telnet.htm
 */
class SmtpSocket extends EmailSocket {
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
		$stats = $this->write('STAT', 'cleanData');
		$stats =  explode(' ', current(array_keys($stats)));
		if($stats[0] != '+OK') {
			$this->_errors[] = 'Could not get stats';
		}

		if(isset($stats[1])) {
			$this->mailStats['totalCount'] = $stats[1];
		}

		if(isset($stats[2])) {
			$this->mailStats['totalSize'] = $stats[2];
		}

		unset($stats);

		if($this->mailStats['totalSize'] > 0) {
			$this->mailStats['totalSizeReadable'] = convert($this->mailStats['totalSize']);
		}

		return true;
	}

	protected function _getList() {

	}

	protected function _getCapabilities() {

	}

	protected function _getMailboxes($ref = '', $wildcard = '*') {

	}

	public function noop() {

	}

	public function undoDeletes() {

	}

}