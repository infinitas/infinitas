<?php
class TrashController extends ManagementAppController {
	var $name = 'Trash';

	var $uses = array();

	function beforeFilter(){
		parent::beforeFilter();
		if(isset($this->params['form']['action']) && $this->params['form']['action'] == 'cancel'){
			unset($this->params['form']['action']);
			$this->redirect(array_merge(array('action' => 'list_items'), $this->params['named']));
		}
	}

	/**
	* List all table with deleted in the schema
	*/
	function admin_index(){
		App::import('AppModel');
		$this->AppModel = new AppModel(array('table' => false));
		$trashableTables = $this->AppModel->getTablesByField('default', 'deleted');

		$trashed = array();
		foreach($trashableTables as $table ){
			$count = $this->AppModel->query(
				'SELECT COUNT(*) AS `count` FROM `'.$table['table'].'` AS `'.$table['model'].'`   WHERE `'.$table['model'].'`.`deleted` = 1'
			);

			$trashed[] = array(
				'plugin' => $table['plugin'],
				'model'  => $table['model'],
				'table'  => $table['table'],
				'deleted' => $count[0][0]['count']
			);
		}

		$this->set(compact('trashed'));
	}

	/**
	* List all records from selected table.
	*/
	function admin_list_items(){
		if(isset($this->params['named']['pluginName']) && isset($this->params['named']['modelName'])){
			extract($this->params['named']);

			$this->loadModel($pluginName . '.' . $modelName);

			$fieldList = array(
						$modelName . '.id',
						$modelName . '.deleted_date'
			);

			if($this->{$modelName}->hasField('title')) {
				$fieldList[] = $modelName . '.title';
			}

			elseif($this->{$modelName}->hasField('name')) {
				$fieldList[] = $modelName . '.name';
			}


			$this->paginate = array(
				$modelName => array(
					'contain' => false,
					'fields' => $fieldList
				)
			);

			$trashedItems = $this->paginate($modelName, array($modelName . '.deleted' => 1));

			$this->set(compact('trashedItems', 'pluginName', 'modelName'));
		}
		else{
			$this->redirect(array('action' => 'index'));
		}
	}

	function __massActionRestore($ids) {
		if(isset($this->params['named']['pluginName']) && isset($this->params['named']['modelName'])){
			extract($this->params['named']);

			$this->loadModel($pluginName . '.' . $modelName);

			foreach($ids as $id) {
				$this->{$modelName}->undelete($id);
			}

			$prettyModelName = low(Inflector::humanize(Inflector::underscore(Inflector::pluralize($modelName))));
			$this->Session->setFlash(__('The ' . $prettyModelName . ' have been restored', true));
			$this->redirect($this->referer());
		}
		else
		{
			$this->redirect($this->referer());
		}
	}

	function __massActionDelete($ids) {
		if(isset($this->params['named']['pluginName']) && isset($this->params['named']['modelName'])){
			extract($this->params['named']);

			$this->loadModel($pluginName . '.' . $modelName);

			$this->{$modelName}->enableSoftDeletable('find', false);

			$this->MassAction->delete($ids);
		}
		else
		{
			$this->redirect($this->referer());
		}
	}

	function __handleDeletes($ids)
	{
		extract($this->params['named']);
		$this->{$modelName}->enableSoftDeletable('delete', false);

		$conditions = array($modelName . '.' . $this->$modelName->primaryKey => $ids);

		$message = __('deleted', true);

		if($this->{$modelName}->deleteAll($conditions)) {
			$this->Session->setFlash(__('The ' . Inflector::pluralize(low($modelName)) . ' have been', true) . ' ' . $message);
		}
		else {
			$this->Session->setFlash(__('The ' . Inflector::pluralize(low($modelName)) . ' could not be', true) . ' ' . $message);
		}
		$this->redirect($this->data['Confirm']['referer']);
	}
}