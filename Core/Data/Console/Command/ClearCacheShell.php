<?php
/**
 * ClearCache shell
 *
 * PHP versions 4 and 5
 *
 * Copyright 2010, Marc Ypes, The Netherlands
 *
 *
 *
 *
 * @package Infinitas.Data.Console
 * @copyright     2010 Marc Ypes, The Netherlands
 * @author        Ceeram
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Helps clear content of CACHE subfolders as well as content in cache engines from console
 *
 * @package Infinitas.Data.Console
 */
class ClearCacheShell extends AppShell {

/**
 * ClearCache instance
 *
 * @var ClearCache
 */
	public $_Cleaner;

/**
 * Shell startup
 *
 * Initializes $_Cleaner property
 *
 * @return void
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
 * @return array
 */
	public function main() {
		$this->files();
		$this->assets();
		$this->engines();
	}

/**
 * see how much cache is being used by different engines
 *
 * @return void
 */
	public function status() {
		parent::main();
		$this->out("Type \t\tUsed \t\tTotal \t\tAvailable");
		$this->hr();
		foreach(call_user_func_array(array($this->_Cleaner, 'status'), $this->args) as $type => $values) {
			$this->out(sprintf(
				"%s \t%s \t%s \t%s",
				str_pad(Inflector::humanize($type), 10, ' '),
				str_pad(convert($values['used']), 10, ' '),
				str_pad(convert($values['total']), 10, ' '),
				str_pad(convert($values['available']), 10, ' ')
			));
		}
	}

/**
 * Clears content of CACHE subfolders
 *
 * @return void
 */
	public function files() {
		$this->_output(call_user_func_array(array($this->_Cleaner, 'files'), $this->args));
	}

/**
 * Clears content of cache engines
 *
 * @return void
 */
	public function assets() {
		$this->_output(call_user_func_array(array($this->_Cleaner, 'assets'), $this->args));
	}

/**
 * Clears content of cache engines
 *
 * @return void
 */
	public function engines() {
		$this->_output(call_user_func_array(array($this->_Cleaner, 'engines'), $this->args));
	}

/**
 * output the results of the cache clearing
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