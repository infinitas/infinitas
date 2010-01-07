<?php
	/**
	 *
	 *
	 */
	class ThemeAppModel extends AppModel{
		var $tablePrefix = 'core_';

		function beforeSave() {
			parent::beforeSave();

			$this->__clearCache();
			return true;
		}

		function afterDelete() {
			parent::afterDelete();

			$this->__clearCache();
			return true;
		}

		private function __clearCache() {
			App::import('Folder');

			$Folder = new Folder(CACHE . 'core');

			$files = $Folder->read();

			if (empty($files[1])) {
				return true;
			}

			foreach($files[1] as $file) {
				if ($file == 'empty') {
					continue;
				}
				unlink(CACHE . 'core' . DS . $file);
			}

			return true;
		}
	}
?>