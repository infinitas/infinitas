<?php
App::uses('lessc', 'Assets.Lib');

class LessShell extends AppShell {
	public function main() {
		//$this->_write($this->_compile($this->_bootstrap()), InfinitasPlugin::path('Assets') . 'webroot' . DS . 'css' . DS . 'bootstrap.css');
		$this->_write(
			$this->_compile(InfinitasPlugin::path('Assets') . 'Lib' . DS . 'bootstrap' . DS . '0-infinitas.less'),
			InfinitasPlugin::path('Assets') . 'webroot' . DS . 'css' . DS . 'admin.css'
		);
	}

	protected function _write($contents, $file) {
		$contents = explode("\n", $contents);
		foreach($contents as $k => $content) {
			if(substr($content, 0, 7) == '@import') {
				unset($contents[$k]);
			}
		}

		$File = new File($file);
		return $File->write(implode("\n", $contents));
	}

/**
 * Compile list of less files
 *
 * Can pass single file or array of files to be compiled
 *
 * @param array|string $files
 *
 * @return string
 */
	protected function _compile($files) {
		if(is_array($files)) {
			foreach($files as &$file) {
				$File = new File($file);
				$file = $File->read();
			}
			$files = implode("\n\n", $files);
		}
		$LessC = new lessc();

		if(is_readable($files)) {
			return $LessC->compileFile($files);
		}

		return $LessC->compile($files);
	}

/**
 * Get bootstrap less files
 *
 * @return array
 */
	protected function _bootstrap() {
		$files = glob(InfinitasPlugin::path('Assets') . 'Lib' . DS . 'bootstrap' . DS . '*.less');

		foreach($files as $k => $file) {
			if(strstr($file, '/variables.less') !== false) {
				unset($files[$k]);
			}
		}

		return $files;
	}
}
