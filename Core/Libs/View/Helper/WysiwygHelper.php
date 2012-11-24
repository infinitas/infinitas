<?php
/**
 * WysiwygHelper
 *
 * @package Infinitas.Libs.Helper
 */

App::uses('InfinitasHelper', 'Libs.View/Helper');

/**
 * WysiwygHelper
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Libs.Helper
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class WysiwygHelper extends InfinitasHelper {
/**
 * Helpers to load
 *
 * @var array
 */
	public $helpers = array(
		'Form'
	);

/**
 * Load the wysiwyg editor
 *
 * @param string $editor
 * @param string $field
 * @param array $config
 *
 * @return string
 */
	public function load($editor = null, $field = null, $config = array()) {
		$helperName = sprintf('Wysiwyg%s', Inflector::Classify($editor));

		switch($editor) {
			case 'text':
			case CakePlugin::loaded($helperName) == false:
				return $this->text($field);
				break;
		}

		try{
			App::uses($helperName . 'Helper', $helperName . '.View/Helper');
			$this->Editor = $this->_View->Helpers->load($helperName . '.' . $helperName);
		}

		catch(MissingHelperException $e) {
			return $this->input($field, array('style' => 'width:98%; height:500px;')) . $e->getMessage();
		}

		$fields = explode('.', $field);

		$heading = '<div><h3>' . __d('libs', ucfirst(isset($fields[1]) ? $fields[1] : $fields[0])).'</h3>';
		return $heading . $this->input($field, array('label' => false)) . $this->Editor->editor($field, $config) . '</div>';
	}

/**
 * Wysiwyg textarea
 *
 * @param string $field
 *
 * @return string
 */
	public function text($id = null) {
		return $this->input($id, array('type' => 'textarea'));
	}

/**
 * Wysiwyg form input
 *
 * @param string $field field
 * @param array $options
 *
 * @return string
 */
	public function input($field, $options = array('style' => 'width:98%; height:500px;')) {
		return $this->Form->input($field, $options);
	}

}