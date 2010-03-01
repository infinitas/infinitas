<?php
class TrashController extends ManagementAppController {
	var $name = 'Trash';
	
	var $uses = array();

	function beforeFilter(){
		if(isset($this->params['form']['action']) && $this->params['form']['action'] == 'cancel'){
			unset($this->params['form']['action']);
			$this->redirect(array_merge(array('action' => 'list_items'), $this->params['named']));
		}		
		
		parent::beforeFilter();
		$this->db = ConnectionManager::getDataSource('default');
	}

	function admin_index(){
		$trashableTables = $this->_getTrashableTables();

		foreach($trashableTables as $table ){
			$count = $this->db->query(
				'SELECT COUNT(*) AS `count` FROM `'.$table['table'].'` AS `'.$table['model'].'`   WHERE `'.$table['model'].'`.`deleted` = 1'
			);

			$trashed[] = array(
				'plugin' => $table['plugin'],
				'model'  => $table['model'],
				'table'  => $table['table'],
				'deleted' => $count[0][0]['count']
			);
		}

		$this->set('trashed', $trashed);
	}

	function admin_list_items(){
		if(isset($this->params['named']['pluginName']) && isset($this->params['named']['modelName'])){
			extract($this->params['named']);
			
			$this->loadModel($pluginName . '.' . $modelName);
			
			$this->paginate = array(
				$modelName => array(
					'contain' => false,
					'fields' => array(
						$modelName . '.id',
						$modelName . '.title',
						$modelName . '.deleted_date'
					)
				)
			);
			
			$trashedItems = $this->paginate($modelName, array($modelName . '.deleted' => 1));
			
			$this->set(compact('trashedItems', 'pluginName', 'modelName'));
		}
		else
		{
			$this->redirect(array('index'));
		}
	}

	function _getTables(){
		$tables = $this->db->query('SHOW TABLES;');
		return Set::extract('/TABLE_NAMES/Tables_in_infinitas', $tables);
	}

	function _getTrashableTables(){
		$tableNames = $this->_getTables();
		$lockableTables = array();

		foreach($tableNames as $table ){
			$fields = $this->db->query('DESCRIBE '.$table);
			$fields = Set::extract('/COLUMNS/Field', $fields);

			if (in_array('deleted', $fields)) {
				$_table = explode('_', $table, 2);
				$lockableTables[] = array(
					'plugin' => ucfirst($_table[0]),
					'model'  => Inflector::classify($_table[1]),
					'table'  => $table
				);
			}
		}

		return $lockableTables;
	}
	
	function __massActionRestore($ids) {
		if(isset($this->params['named']['pluginName']) && isset($this->params['named']['modelName'])){
			extract($this->params['named']);
			
			$this->loadModel($pluginName . '.' . $modelName);

			foreach($ids as $id) {
				$this->{$modelName}->undelete($id);
			}
			
			$this->Session->setFlash(__('The ' . Inflector::pluralize(low($modelName)) . ' have been restored', true));
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
			
			parent::__massActionDelete($ids);
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