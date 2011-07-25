<?php
	class InfinitasAppShell extends Shell {
		public $tasks = array(
			'ProgressBar'
		);

		/**
		 * @brief the width of the terminal
		 * 
		 * @var int
		 */
		public $terminalWidth = null;

		private $__interactiveBuffer = null;

		public function __construct($dispatch){
			$this->__bootstrapPlugins();

			$this->setTerminalWidth();

			parent::__construct($dispatch);
		}

		public function interactiveClear() {
			$this->Dispatch->stdout(str_repeat(' ', $this->terminalWidth - 2) . "\r", false);
			$this->__interactiveBuffer = null;
		}

		public function interactive($text = null, $append = false) {
			if($append === true){
				$this->__interactiveBuffer .=  $text;
			}
			else{
				$this->__interactiveBuffer = $text;
			}

			$this->Dispatch->stdout(str_pad($this->__interactiveBuffer, $this->terminalWidth - 1) . "\r", false);
		}

		private function __bootstrapPlugins() {
			if(!class_exists('Folder')){
				App::import('Folder');
			}

			if($paths === false){
				$Folder = new Folder(APP);
				$folders = $Folder->read();
				$folders = array_flip($folders[0]);
				unset($Folder, $folders['.git'], $folders['config'], $folders['locale'],
					$folders['nbproject'], $folders['tmp'], $folders['views'],
					$folders['webroot'], $folders['tests']);

				$paths = array();
				foreach(array_flip($folders) as $folder){
					$paths[] = APP . $folder . DS;
				}

				Cache::write('plugin_paths', $paths);
				unset($Folder, $folders);

				// @todo trigger event to get oter plugin paths
			}

			App::build(
				array(
					'plugins' => $paths
				)
			);

			unset($paths);
		}
	}