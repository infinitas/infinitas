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

class Backup extends CoreAppModel {
	var $name = 'Backup';

	var $tablePrefix = 'core_';

	var $last_id = 0;
	/**
	* Constructor
	*
	* @access protected
	*/
	function getLastBackup($model = null, $plugin = null) {
		$lastBackup = $this->find(
			'first',
			array(
				'fields' => array(
					'Backup.last_id'
					),
				'conditions' => array(
					'Backup.plugin' => $plugin,
					'Backup.model' => $model
					),
				'order' => array(
					'Backup.id' => 'DESC'
					)
				)
			);

		if (!empty($lastBackup)) {
			$this->last_id = $lastBackup['Backup']['last_id'];
		}

		return $this->last_id;
	}

	function getRecordsForBackup($Model) {
		return $Model->find(
			'all',
			array(
				'conditions' => array($Model->name . '.id > ' => $this->last_id
					),
				'contain' => false
				)
			);
	}
}

?>