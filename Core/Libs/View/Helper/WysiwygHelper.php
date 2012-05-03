<?php
	/**
	 * Comment Template.
	 *
	 * @todo -c Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   sort
	 * @subpackage	sort.comments
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since		 0.5a
	 */

	class WysiwygHelper extends InfinitasHelper {
		public $helpers = array(
			'Form'
		);

		public function load($editor = null, $field = null, $config = array()) {
			$helperName = sprintf('Wysiwyg%s', Inflector::Classify($editor));
			
			switch($editor) {
				case 'text':
				case CakePlugin::loaded($helperName) == false:
					return $this->text($field);
					break;
			} // switch

			try{
				App::uses($helperName . 'Helper', $helperName . '.View/Helper');
				$this->Editor = $this->_View->Helpers->load($helperName . '.' . $helperName);
			}
			
			catch(MissingHelperException $e) {
				return $this->input($field, array('style' => 'width:98%; height:500px;')) . $e->getMessage();
			}
			
			$fields = explode('.', $field);

			$heading = '<div><h3>' . __(ucfirst(isset($fields[1]) ? $fields[1] : $fields[0])).'</h3>';
			return $heading . $this->input($field, array('label' => false)) . $this->Editor->editor($field, $config) . '</div>';
		}

		public function text($id = null){
			return $this->input($id, array('type' => 'textarea'));
		}

		public function input($id, $params = array('style' => 'width:98%; height:500px;')) {
			return $this->Form->input($id, $params);
		}
	}