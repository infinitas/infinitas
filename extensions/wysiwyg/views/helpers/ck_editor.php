<?php
	class CkEditorHelper extends Helper{
		/**
		 * Other helpers used by FormHelper
		 *
		 * @var array
		 * @access public
		 */
		var $helpers = array('Html');

		/**
		 * @var array
		 * @access public
		 */
		var $configs = array();

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

		/**
		* Load required js before rendering the page.
		*/
		public function beforeRender() {
			$this->Html->script('/wysiwyg/js/ck_editor/ckeditor.js', false);
		}
	}
?>