<?php
/**
 * Backup
 *
 * @package Infinitas.Management.Model
 */

/**
 * Backup
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Management.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class Backup extends ManagementAppModel {
/**
 * Last backup id
 *
 * @var string
 */
	public $lastId;

/**
 * Get the last backup
 *
 * @param string $model the model name
 * @param string $plugin the plugin name
 *
 * @return string
 */
	public function getLastBackup($model = null, $plugin = null) {
		$lastBackup = $this->find(
			'first',
			array(
				'fields' => array(
					$this->alias . '.last_id'
				),
				'conditions' => array(
					$this->alias . '.plugin' => $plugin,
					$this->alias . '.model' => $model
				),
				'order' => array(
					$this->alias . '.id' => 'DESC'
				)
			)
		);

		if (!empty($lastBackup[$this->alias]['last_id'])) {
			$this->lastId = $lastBackup[$this->alias]['last_id'];
		}

		return $this->lastId;
	}

/**
 * Get the records to be backed up
 *
 * @param Model $Model the model being backed up
 *
 * @return array
 */
	public function getRecordsForBackup($Model) {
		return $Model->find(
			'all',
			array(
				'conditions' => array(
					$Model->alias . '.id > ' => $this->lastId
				),
				'contain' => false
			)
		);
	}

}