<?php
/**
 * Infinitas Helper.
 *
 * Does a lot of stuff like generating ordering buttons, load modules and
 * other things needed all over infinitas.
 *

 *
 * @filesource
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Libs.Helper
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton ( dogmatic69 )
 *
 *
 *
 */
App::uses('CakeTime', 'Utility');
App::uses('AppHelper', 'View/Helper');

class InfinitasHelper extends AppHelper {
/**
 * other helpers being used
 *
 * @var array
 */
	public $helpers = array(
		'Html',
		'Form',
		'Libs.Design',
		'Libs.Image',
		'Libs.Wysiwyg'
	);

/**
 * JSON errors.
 *
 * Set up some errors for json.
 */
	public $jsonErrors = array(
		JSON_ERROR_NONE	  => 'No error',
		JSON_ERROR_DEPTH	 => 'The maximum stack depth has been exceeded',
		JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
		JSON_ERROR_SYNTAX	=> 'Syntax error',
	);

	protected $_menuData = '';

	protected $_menuLevel = 0;

	public $View = null;

	private $__massActionCheckBoxCounter = 0;

/**
 * Set to true when the menu has a current marker to avoid duplicates.
 * @var unknown_type
 */
	protected $_currentCssDone = false;

/**
 * Create a status icon.
 *
 * Takes a int 0 || 1 and returns a icon with title tags etc to be used
 * in places like admin to show iff something is on/off etc.
 *
 * @param int $status the status tinyint(1) from a db find.
 *
 * @return string
 */
	public function status($status, $options = array()) {
		$params = array('class' => 'icon-status');

		$options = array_merge(
			array(
				'title_yes' => __d('infinitas', 'Status :: This record is active'),
				'title_no' => __d('infinitas', 'Status :: This record is disabled'),
			),
			(array)$options
		);


		if(in_array((string)strtolower($status), array('1', 'yes', 'on'))) {
			$params['title'] = $options['title_yes'];
			$params['alt'] = __d('infinitas', 'On');
			return $this->Image->image('status', 'active', $params);
		}

		$params['title'] = $options['title_no'];
		$params['alt'] = __d('infinitas', 'Off');
		return $this->Image->image('status', 'inactive', $params);
	}

/**
 * generate a checkbox for rows that use mass_action stuff
 *
 * it will keep track of the $i for the checkbox number so there are no duplicates.
 * MassActionComponent::filter() will remove these fields from the searches so there
 * are no sql errors.
 *
 * @param array $data the row from find either find('first') or find('all')[x]
 * @param array $options set the fk or model manually
 *
 * @return a checkbox
 */
	public function massActionCheckBox(array $data, $options = array()) {
		$alias = current(array_keys($this->request->params['models']));
		$modelClass = implode('.', $this->request->params['models'][$alias]);
		$options = array_merge(
			array('alias' => $alias, 'hidden' => false, 'checked' => false, 'primaryKey' => false),
			$options
		);
		if(empty($options['primaryKey'])) {
			$options['primaryKey'] = ClassRegistry::init($modelClass)->primaryKey;
		}

		if(empty($data[$options['alias']]) || !isset($data[$options['alias']][$options['primaryKey']])) {
			return false;
		}

		$checkbox = $this->Form->checkbox(
			$options['alias'] . '.' . $this->__massActionCheckBoxCounter++ . '.' . 'massCheckBox',
			array(
				'value' => $data[$options['alias']][$options['primaryKey']],
				'hidden' => $options['hidden'],
				'checked' => $options['checked']
			)
		);

		return $checkbox;
	}

/**
 * generate a date display box
 *
 * @param string|array $date a date string or record with created / modified date
 * @param string $method the CakeTime method to use
 *
 * @return string
 */
	public function date($date, $method = 'niceShort') {
		if(!method_exists('CakeTime', $method)) {
			return false;
		}

		if(is_array($date)) {
			if(!empty($date['modified'])) {
				$date = $date['modified'];
			} elseif(!empty($date['created'])) {
				$date = $date['created'];
			} else {
				$date = null;
			}
		}

		if(empty($date)) {
			return false;
		}

		return $this->Html->tag(
			'div',
			$this->Html->tag('span', call_user_func('CakeTime::' . $method, $date), array('class' => $method)) .
			$this->Html->tag('span', $date, array('class' => 'full')),
			array('class' => 'date')
		);
	}
}
