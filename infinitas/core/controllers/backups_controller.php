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

class BackupsController extends CoreAppController {
	var $name = 'Backups';

	function admin_backup() {
		if (!isset($this->params['named']['m'])) {
			$this->Session->setFlash(__('No model to backup.', true));
			$this->redirect($this->referer());
		}

		$fullModel = $model = Inflector::classify($this->params['named']['m']);
		$plugin = '';
		if (isset($this->params['named']['p']) && $this->params['named']['p'] != '') {
			$plugin = Inflector::classify($this->params['named']['p']);
			$fullModel = $plugin . '.' . $fullModel;
		}

		$this->Backup->getLastBackup($model, $plugin);

		$Model = ClassRegistry::init($fullModel);

		$data['Backup']['plugin'] = $plugin;
		$data['Backup']['model'] = $model;
		$data['Backup']['last_id'] = $this->__checkBackups($Model);
		$data['Backup']['data'] = serialize($this->Backup->getRecordsForBackup($Model));

		$this->__saveBackup($data, $Model);
	}

	/**
	* check the backups.
	*
	* First sees if there is any records in the model, then checks if the
	* last backup is older than the current records.
	*
	* if there is nothing or the current id in the backups is the same or
	* greater than the current it will just redirect with a message
	*
	* @param mixed $Model from {@see ClassRegistry::init }
	* @return int the id of the newest record in the model being backed up
	*/
	function __checkBackups($Model) {
		$newLastId = $Model->find(
			'first',
			array(
				'fields' => array($Model->name . '.id'
					),
				'conditions' => array($Model->name . '.id > ' => $this->Backup->last_id
					),
				'order' => array($Model->name . '.id' => 'DESC'
					),
				'contain' => false
				)
			);

		if (empty($newLastId)) {
			$this->Session->setFlash(__('Nothing to backup', true));
			$this->redirect($this->referer());
		}

		if (isset($newLastId[$Model->name]['id']) && $this->Backup->last_id >= $newLastId[$Model->name]['id']) {
			$this->Session->setFlash(__('Nothing new to backup', true));
			$this->redirect($this->referer());
		}

		return $newLastId[$Model->name]['id'];
	}

	/**
	* Saves the data to the backup table.
	*
	* the records from the backed up model are serialized and saved in
	* "data" field.
	*
	* @param mixed $data the array for a cakephp save
	* @return n /a will redirect with a message.
	*/
	function __saveBackup($data, $Model) {
		if (isset($Model->hasAndBelongsToMany)) {
			foreach($Model->hasAndBelongsToMany as $__model => $relation) {
				if (isset($relation['joinTable'])) {
					Inflector::classify($relation['joinTable']);
				}
			}
		}

		$this->Backup->create();
		if ($this->Backup->save($data)) {
			$this->Session->setFlash(sprintf(__('Your %s are backed up', true), Inflector::pluralize(Inflector::humanize($data['Backup']['model']))));
			$this->redirect($this->referer());
		}

		$this->Session->setFlash(__('There was a problem with the backup, please try again.', true));
		$this->redirect($this->referer());
	}
}

?>