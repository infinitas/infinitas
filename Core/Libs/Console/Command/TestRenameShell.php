<?php
	class TestRenameShell extends AppShell {
		public function main() {
			Configure::write('debug', 2);
			foreach(CakePlugin::loaded() as $plugin) {
				$path = App::pluginPath($plugin) . 'Test';
				$this->__moveFolders($path);
			}
		}

		private function __moveFolders($path) {
			$this->Folder = new Folder($path);
			$folders = $this->Folder->read();
			
			foreach($folders[0] as $folder) {
				$old = $path . DS . $folder;
				$new = $path . DS . Inflector::camelize($folder);
				if($folder != Inflector::camelize($folder)) {
					echo "moving $old to $new \n";
				}

				$this->__moveFolders($new);
				$this->__moveFiles($new);
			}
		}

		private function __moveFiles($path) {
			$this->Folder = new Folder($path);
			$files = $this->Folder->read();
			
			foreach($files[1] as $file) {
				$old = $path . DS . $file;

				$file = explode('.', $file, 2);
				if($file[1] == 'php') {
					$file[1] = '.' . $file[1];
				}
				$new = $path . DS . Inflector::camelize($file[0]) . ucfirst($file[1]);
				var_dump($file);
				if($old != $new) {
					echo "moving $old to $new \n";
				}
			}
		}
	}