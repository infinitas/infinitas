<?php
	App::uses('InstallerLib', 'Installer.Lib');
	
	class InstallShell extends AppShell {
		public $tasks = array(
			'Installer.Installer', 'Installer.InfinitasPlugin'
			);

		/**
		 * help
		 */
		public function help() {
			$this->h1('Interactive Install Shell Help');
			$this->p(
				'The interactive shell is for installing / deploying Infinitas '.
				'powered plugins. You will be asked to enter your database credentials '.
				'and then attempt to install the application. '
			);

			$this->h2(
				'Available Options'
			);

			$this->li(
				array(
					'cake install - interactive installer',
					'cake install all - full installation',
					'cake install plugin - install a particular plugin'
				)
			);

			$this->helpPause();
		}

		public function  __construct(&$dispatch) {
			parent::__construct($dispatch);

			$this->InstallerLib = new InstallerLib();
		}

		public function main() {			
			do {
				$this->h1('Interactive Install Shell');
				$this->li(
					array(
						'[E]verything',
						'[P]lugin',
						'[M]odule',
						'[T]heme',
						'Plugin [A]dd-on',
						'[L]icence',
						'[H]elp',
						'[Q]uit'
					)
				);
				$this->br();
				$input = strtoupper($this->in('What do you wish to release?'));

				switch ($input) {
					case 'E':
						$this->everything();
						break;

					case 'P':
						$this->plugin();
						break;

					case 'M':
						$this->module();
						break;

					case 'T':
						$this->theme();
						break;

					case 'A':
						$this->addon();
						break;

					case 'H':
						$this->help();
						break;

					case 'L':
						$this->license();
						$this->helpPause();
						break;

					case 'Q':
						$this->quit();
						break;

					default:
						$this->p('Invalid option');
						break;
				}
			} while($input != 'Q');
		}

		/**
		 * install a clean app
		 */
		public function everything() {
			foreach($this->InstallerLib->steps as $step) {
				print_r($this->Installer->{$step}());
			}
		}

		/**
		 * install a plugin
		 */
		public function plugin() {
			do {
				$this->h1('Interactive Install Shell');
				$this->li(
					array(
						'[U]pdate a plugin',
						'[Z]ip file install',
						'[O]ver the air',
						'[L]ocal install',
						'[H]elp',
						'[Q]uit'
					)
				);
				$this->br();
				$input = strtoupper($this->in('What do you wish to release?'));

				switch ($input) {
					case 'U':
						$this->Installer->updatePlugin();
						break;

					case 'Z':
						$this->Installer->installFromZipFile();
						break;

					case 'L':
						$this->Installer->installLocalPlugin();
						break;

					case 'H':
						$this->p('If you have updated your code and ' .
							'need to update the plugin use [U]pdate');
						$this->p('If you have downloaded a file and ' .
							'need to install from the zip file use [Z]ip file');
						$this->p('If you would like infinitas to downlad ' .
							'and install the plugin use [O]ver the air');
						$this->p('If you have writen a custom plugin ' .
							'and would like it installed use [L]ocal install');
						$this->helpPause();
						break;

					default:
						$this->p('Invalid option');
						break;
				}
			} while($input != 'Q');

		}

		/**
		 * install a module
		 */
		public function module() {

		}

		/**
		 * install a theme
		 */
		public function theme() {

		}

		/**
		 * install an addon
		 */
		public function addon() {

		}

		/**
		 * display the license
		 */
		public function license() {
			$this->Installer->displayLicence(false);
		}
	}
