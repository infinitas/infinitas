<?php
/**
 * ClearCache shell
 *
 * PHP versions 4 and 5
 *
 * Copyright 2010, Marc Ypes, The Netherlands
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @package       app
 * @subpackage    app.plugins.clear_cache.vendors.shells
 * @copyright     2010 Marc Ypes, The Netherlands
 * @author        Ceeram
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Helps clear content of CACHE subfolders as well as content in cache engines from console
 *
 * @package       app
 * @subpackage    app.plugins.clear_cache.Console.Command
 */
class ClearCacheShell extends Shell {

/**
 * ClearCache instance
 *
 * @var ClearCache
 * @access protected
 */
	public $_Cleaner;

/**
 * Shell startup
 *
 * Initializes $_Cleaner property
 *
 * @return void
 * @access public
 */
	public function startup() {
		App::uses('ClearCache', 'Data.Lib');
		$this->_Cleaner = new ClearCache();
	}

/**
 * Main shell method
 *
 * Clears content of CACHE subfolders and configured cache engines
 *
 * @return array associative array with cleanup results
 * @access public
 */
	public function main() {
		$this->files();
		$this->assets();
		$this->engines();
	}

/**
 * Clears content of CACHE subfolders
 *
 * @return void
 * @access public
 */
	public function files() {
		$this->_output(call_user_func_array(array($this->_Cleaner, 'files'), $this->args));
	}

/**
 * Clears content of cache engines
 *
 * @return void
 * @access public
 */
	public function assets() {
		$this->_output(call_user_func_array(array($this->_Cleaner, 'assets'), $this->args));
	}

/**
 * Clears content of cache engines
 *
 * @return void
 * @access public
 */
	public function engines() {
		$this->_output(call_user_func_array(array($this->_Cleaner, 'engines'), $this->args));
	}

/**
 * @brief output the results of the cache clearing
 *
 * @param array $output
 *
 * @return void
 */
	protected function _output(array $output) {
		foreach ($output as $k => $v) {
			if(is_array($v)) {
				foreach ($v as $vv) {
					$this->out($k . ': ' . $vv);
				}
			} else {
				$this->out($k . ': ' . ($v ? 'cleared' : 'error'));
			}
		}
	}

}