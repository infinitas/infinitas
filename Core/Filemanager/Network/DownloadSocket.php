<?php
/**
 * DownloadSocket
 *
 * @package Infinitas.Filemanager.Network
 */

App::uses('HttpSocket', 'Network/Http');

/**
 * DownloadSocket
 *
 * @package Infinitas.Filemanager.Network
 */

class DownloadSocket extends HttpSocket {
/**
 * Constructor
 *
 * Disable ssl as it was not working with redirects
 *
 * @param array $config the config
 *
 * @return void
 */
	public function __construct($config = array()) {
		parent::__construct(array_merge(array(
			'ssl_verify_peer' => false
		), $config));
	}

/**
 * Download the specified file
 *
 * @param string $from the download location
 * @param string $to the path to save to
 *
 * @return File|boolean
 */
	public function download($from, $to = null) {
		if (empty($to)) {
			$to = TMP . 'downloads' . DS . String::uuid() . basename($from);
		}

		$this->get($from, array(), array('redirect' => true));

		$this->File = new File($to, true, 0775);
		if ($this->response->code == '200' && $this->File->write($this->response->body)) {
			return true;
		}
		return false;
	}

	public function pwd() {
		if (empty($this->File)) {
			return false;
		}

		return $this->File->pwd();
	}

}