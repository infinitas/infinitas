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
* @link http://infinitas-cms.org
* @package sort
* @subpackagesort .comments
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.5a
*/

class LockableBehavior extends ModelBehavior {
	/**
	* Contain default settings.
	*
	* @var array
	* @access protected
	*/
	var $_defaults = array(
		'fields' => array(
			'locked_by' => 'locked_by',
			'locked_since' => 'locked_since',
			'locked' => 'locked'
			)
		);

	/**
	*
	* @param object $Model Model using the behavior
	* @param array $settings Settings to override for model.
	* @access public
	* @return void
	*/
	function setup(&$Model, $config = null) {
		if (is_array($config)) {
			$this->settings[$Model->alias] = array_merge($this->_defaults, $config);
		} else {
			$this->settings[$Model->alias] = $this->_defaults;
		}
	}

	function lock(&$Model, $fields = null, $id = null) {
		$Model->contain();
		if($data = $Model->read($this->settings[$Model->alias]['fields'], $id) == false) {
			return false;
		}
		$this->Session = new CakeSession();
		$user_id = $this->Session->read('Auth.User.id');
		if($data[$Model->alias]['locked'] && $data[$Model->alias]['locked_by'] != $user_id) {
			return false;
		}
		$data[$Model->alias] = array(
			'id' => $id,
			$this->settings[$Model->alias]['fields']['locked'] => 1,
			$this->settings[$Model->alias]['fields']['locked_by'] => $user_id,
			$this->settings[$Model->alias]['fields']['locked_since'] => date('Y-m-d H:i:s')
			);
		$Model->save($data, array('validate' => false, 'callbacks' => false));
		$data = $Model->read($fields, $id);
		return $data;
	}

	function beforeSave($Model) {
		$Model->data[$Model->name]['locked'] = 0;
		$Model->data[$Model->name]['locked_by'] = null;
		$Model->data[$Model->name]['locked_since'] = null;
		return true;
	}
}

?>