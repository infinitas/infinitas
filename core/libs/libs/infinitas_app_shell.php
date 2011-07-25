<?php
	class InfinitasAppShell extends Shell {
		public function __construct($dispatch){
			$this->__bootstrapPlugins();

			parent::__construct($dispatch);
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