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
		var $helpers = array(
			'Form',
			'Html',
			'Js'
		);

		public function load($editor = null, $field = null, $config = array()){
			switch($editor){
				case 'text':
					return $this->text($field);
					break;
			} // switch

			$helperName = sprintf('Wysiwyg%s', Inflector::Classify($editor));
			//App::uses($helperName . 'Helper', $helperName . '.View');
			App::uses('WysiwygTinyMceHelper', 'WysiwygTinyMce.View/Helper');
			try{
				$this->Editor = $this->_View->Helpers->load('WysiwygTinyMce');
			}
			catch(MissingHelperException $e) {
				
			}

			if (!$this->Editor instanceof Helper) {
				return $this->input($field, array('style' => 'width:98%; height:500px;', 'value' => $e->getMessage()));
			}

			$fields = explode('.', $field);

			$heading = '<div><h3>' . __(ucfirst(isset($fields[1]) ? $fields[1] : $fields[0])).'</h3>';
			return $heading . $this->input($field, array('label' => false)) . $this->Editor->editor($field, $config) . '</div>';
		}

		public function text($id = null){
			return $this->input($id, array('type' => 'textarea'));
		}

		public function input($id, $params = array('style' => 'width:98%; height:500px;')){
			return $this->Form->input($id, $params);
		}
	}