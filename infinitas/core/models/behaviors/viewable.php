<?php
/**
* Comment Template.
*
* @todo Implement .this needs to be sorted out.
*
* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* @filesource
* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
* @link http://www.dogmatic.co.za
* @package sort
* @subpackage sort.comments
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.5a
*/

class ViewableBehavior extends ModelBehavior {
	/**
	* Contain settings indexed by model name.
	*
	* @var array
	* @access private
	*/
	var $__settings = array();

	/**
	* Initiate behavior for the model using specified settings.
	* Available settings:
	*
	* - view_counter: string :: the field in the table that has the count
	*
	* @param object $Model Model using the behaviour
	* @param array $settings Settings to override for model.
	* @access public
	*/
	function setup(&$Model, $settings = array()) {
		$default = array(
			'view_counter' => 'views'
			);

		if (!isset($this->__settings[$Model->alias])) {
			$this->__settings[$Model->alias] = $default;
		}

		$this->__settings[$Model->alias] = am($this->__settings[$Model->alias], ife(is_array($settings), $settings, array()));
	}

	/**
	* This happens after a find happens.
	*
	* @param object $Model Model about to be saved.
	* @return boolean true if save should proceed, false otherwise
	* @access public
	*/
	function afterFind(&$Model, $data) {
		if (isset($data[0]) && count($data) > 1) {
			return $data;
		}

		if (isset($data[0][$Model->alias][$this->__settings[$Model->alias]['view_counter']])) {
			$data[0][$Model->alias][$this->__settings[$Model->alias]['view_counter']]++;

			$Model-> {
				$Model->primaryKey} = $data[0][$Model->alias][$Model->primaryKey];
			$Model->saveField($this->__settings[$Model->alias]['view_counter'],
				$data[0][$Model->alias][$this->__settings[$Model->alias]['view_counter']]
				);
		}

		return $data;
	}
}

?>