<?php
	App::import('Lib', 'Installer.Installer');
	
	class InstallShell extends Shell {
		public $tasks = array('Infinitas', 'Installer', 'Plugin');
        
        /**
         * help
         */
		public function help(){
			$this->Infinitas->h1('Interactive Install Shell Help');
			$this->Infinitas->p(
				'The interactive shell is for installing / deploying Infinitas '.
				'powered plugins. You will be asked to enter your database credentials '.
				'and then attempt to install the application. '
			);

			$this->Infinitas->h2(
				'Available Options'
			);

			$this->Infinitas->li(
				array(
					'cake install - interactive installer',
					'cake install all - full installation',
					'cake install plugin - install a particular plugin'
				)
			);

			$this->Infinitas->helpPause();
		}

		public function  __construct(&$dispatch) {
			parent::__construct($dispatch);

			$this->InstallerLib = new InstallerLib();
		}

		public function main() {			
			do {
				$this->Infinitas->h1('Interactive Install Shell');
				$this->Infinitas->li(
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
				$this->Infinitas->br();
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
						$this->Infinitas->helpPause();
						break;

					case 'Q':
						$this->Infinitas->quit();
						break;

					default:
						$this->Infinitas->p('Invalid option');
						break;
				}
			} while($input != 'Q');
		}

		/**
		 * install a clean app
		 */
		public function everything(){
			foreach($this->InstallerLib->steps as $step){
				print_r($this->Installer->{$step}());
			}
		}

		/**
		 * install a plugin
		 */
		public function plugin(){

		}

		/**
		 * install a module
		 */
		public function module(){

		}

		/**
		 * install a theme
		 */
		public function theme(){

		}

		/**
		 * install an addon
		 */
		public function addon(){

		}

		/**
		 * display the license
		 */
		public function license(){
			$this->Installer->displayLicence(false);
		}
    }
