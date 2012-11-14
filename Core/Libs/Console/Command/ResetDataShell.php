<?php
/**
 * ResetDataShell
 *
 * @package Infinitas.Installer.Console
 */

/**
 * ResetDataShell
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Installer.Console
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ResetDataShell extends Shell {
/**
 * Models to load
 *
 * @var array
 */
	public $uses = array(
		'Installer.Release'
	);

/**
 * Main reset data method
 *
 * @return void
 */
	public function main() {
			$this->out('Reseting database');
			$this->Release->installData(true);
			$this->out('Done');
	}
	
}