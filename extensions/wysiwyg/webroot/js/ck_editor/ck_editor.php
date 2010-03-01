<?php
	/**
	 * TinyMce Wysiwig Helper
	 *
	 * This is the helper to load and cofigure the tiny mce editor for infinitas
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package wysiwyg
	 * @subpackage wysiwyg.helpers.ck_editor
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.6a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class CkEditorHelper extends Helper{
		/**
		 * @var array
		 * @access protected
		 */
		var $_defaults = array();

		/**
		 * Load the editor.
		 *
		 * This is the method that will replace the html element with the editor
		 *
		 * @param sring $fieldName the name of the field to replace.
		 *
		 * @return string the javascript code to load the editor.
		 */
		function editor($fieldName = null, $config = array()){
			$did = $lines = '';

			foreach (explode('.', $fieldName) as $v) {
				$did .= ucfirst($v);
			}

			$options = array_merge($this->_defaults, $config);

			$lines = array();

			foreach ($options as $option => $value) {
				$lines[] = $option . ' : "' . $value . '"';
			}

			$lines = implode(', ', $lines);

			App::import('Helper', 'Html');
			$this->Html = new HtmlHelper();

			return $this->Html->scriptBlock("CKEDITOR.replace( '$did', { $lines });");
		}
	}
?>