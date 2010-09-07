<?php
	/**
	 * Blog App Model
	 *
	 * This is the model that all models in the blog plugin will extend.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package sort
	 * @subpackage sort.comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
 	 */

	class BlogAppModel extends AppModel {
		/**
		 * the table prefix for the plugin
		 * 
		 * @var string
		 * @access public
		 */
		public $tablePrefix = 'blog_';

		/**
		 * before saving
		 */
		public function beforeSave() {
			parent::beforeSave();

			$this->__clearCache();
			return true;
		}

		/**
		 * after delete
		 */
		public function afterDelete() {
			parent::afterDelete();

			$this->__clearCache();
			return true;
		}

		/**
		 * clear cache after data has changed
		 */
		private function __clearCache() {
			App::import('Folder');

			$Folder = new Folder(CACHE . 'blog');

			$files = $Folder->read();

			if (empty($files[1])) {
				return true;
			}

			foreach($files[1] as $file) {
				if ($file == 'empty') {
					continue;
				}
				unlink(CACHE . 'blog' . DS . $file);
			}

			return true;
		}
	}