<?php
/**
 * ReleaseShell
 *
 * @package Infinitas.Installer.Console
 */

/**
 * ReleaseShell
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Installer.Console
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 * @author dakota
 */

class ReleaseShell extends AppShell {
/**
 * Tasks to load
 *
 * @var array
 */
	public $tasks = array(
		'Installer.InfinitasPlugin',
		'Installer.InfinitasReleaseStatus'
	);

/**
 * Display release help
 *
 * @todo use 2.0 help
 *
 * @return void
 */
	public function help() {
		$this->h1('Interactive Release Shell Help');
		$this->p(
			'The interactive shell is for generating a release for your Infinitas '.
			'powered plugin. It will allow you to enter the description and name '.
			'along with setting any dependencies. After you have answered some '.
			'questions all the necesery files will be generated to allow others '.
			'to use the plugin via the installer.'
		);
		$this->p(
			'This shell is also used for creating updates to your plugins by the' .
			'same method as creating the initial release. The release shell will ' .
			'provide you with update options if a previous release has been found.'
		);

		$this->h2('Available Options');

		$this->li(
			array(
				'cake release - For normal plugins',
				'cake release all - If you are creating a core plugin'
			)
		);

		$this->helpPause();
	}

/**
 * Release shell main method
 *
 * @return void
 */
	public function main() {
		do {
			$this->h1('Interactive Release Shell');
			$this->li(
				array(
					'[P]lugin',
					'[M]odule',
					'[T]heme',
					'Plugin [A]dd-on',
					'[S]tatus',
					'[H]elp',
					'[Q]uit'
				)
			);
			$this->br();
			$input = strtoupper($this->in('What do you wish to release?'));

			switch ($input) {
				case 'P':
					$this->InfinitasPlugin->execute();
					break;

				case 'M':
					break;

				case 'T':
					break;

				case 'A':
					break;

				case 'S':
					$this->InfinitasReleaseStatus->execute();
					break;

				case 'H':
					$this->help();
					break;

				case 'Q':
					$this->quit();
					break;

				default:
					$this->out('Invalid option');
					break;
			}
		} while($input != 'Q');
	}
	
}