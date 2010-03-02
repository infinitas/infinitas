<?php
class MassActionComponent extends Object {
	var $name = 'MassAction';

	/**
	* Controllers initialize function.
	*/
	function initialize(&$controller, $settings = array()) {
		$this->Controller = &$controller;
		$settings = array_merge(array(), (array)$settings);
	}

	function getIds($massAction, $data) {
		if (in_array($massAction, array('add','filter'))) {
			return null;
		}

		$ids = array();
		foreach($data as $id => $selected) {
			if (!is_int($id)) {
				continue;
			}

			if ($selected) {
				$ids[] = $id;
			}
		}

		if (empty($ids)) {
			$this->Controller->Session->setFlash(__('Nothing was selected, please select something and try again.', true));
			$this->Controller->redirect(isset($this->Controller->data['Confirm']['referer']) ? $this->Controller->data['Confirm']['referer'] : $this->Controller->referer());
		}

		return $ids;
	}

	function getAction($form) {
		if (isset($form['action'])) {
			return $form['action'];
		}

		$this->Controller->Session->setFlash(__('I dont know what to do.', true));
		$this->Controller->redirect($this->Controller->referer());
	}

	function filter($ids) {
		$data = array();
		foreach( $this->Controller->data[$this->Controller->modelClass] as $k => $field ){
			if ( is_int( $k ) || $k == 'all' ){
				continue;
			}
			$data[$this->Controller->modelClass.'.'.$k] = $field;
		}
		$this->Controller->redirect(array(
				'plugin' => $this->Controller->params['plugin'],
				'controller' => $this->Controller->params['controller'],
				'action' => 'index'
			) + $this->Controller->params['named'] + $data
		);
	}

	function delete($ids) {
		$model = $this->Controller->modelNames[0];

		if (isset($this->Controller->data['Confirm']['confirmed']) && $this->Controller->data['Confirm']['confirmed']) {
			if(method_exists($this->Controller, '__handleDeletes')) {
				$this->Controller->__handleDeletes($ids);
			}
			else {
				$this->__handleDeletes($ids);
			}
		}

		$referer = $this->Controller->referer();
		$rows = $this->Controller->$model->find('list', array('conditions' => array($model.'.id' => $ids)));
		$this->Controller->set(compact('model', 'referer', 'rows'));
		$this->Controller->render('delete', null, APP.'views'.DS.'global'.DS.'delete.ctp');
	}

	function __handleDeletes($ids) {
		$model = $this->Controller->modelNames[0];

		if($this->Controller->{$model}->Behaviors->attached('SoftDeletable')) {
			$result = true;
			foreach($ids as $id) {
				$result = $result && ($this->Controller->{$model}->delete($id) || $this->Controller->{$model}->checkResult());
			}

			$message = __('moved to the trash bin', true);
		}
		else {
			$conditions = array($model . '.' . $this->Controller->$model->primaryKey => $ids);

			$result = $this->Controller->{$model}->deleteAll($conditions);

			$message = __('deleted', true);
		}

		if($result == true) {
			$this->Controller->Session->setFlash(__('The ' . Inflector::pluralize(low($model)) . ' have been', true) . ' ' . $message);
		}
		else {
			$this->Controller->Session->setFlash(__('The ' . Inflector::pluralize(low($model)) . ' could not be', true) . ' ' . $message);
		}
		$this->Controller->redirect($this->Controller->data['Confirm']['referer']);
	}

	function toggle($ids) {
		$model = $this->Controller->modelNames[0];
		$this->Controller->$model->recursive = - 1;
		$ids = $ids + array(0);

		if ($this->Controller->$model->updateAll(
				array($model . '.active' => '1 - `' . $model . '`.`active`'),
					array($model . '.id IN(' . implode(',', $ids) . ')')
					)
				) {
			$this->Controller->Session->setFlash(__('The ' . Inflector::pluralize(low($model)) . ' were toggled', true));
			$this->Controller->redirect($this->Controller->referer());
		}

		$this->Controller->Session->setFlash(__('The ' . Inflector::pluralize(low($model)) . ' could not be toggled', true));
		$this->Controller->redirect($this->Controller->referer());
	}

	function copy($ids) {
		$model = $this->Controller->modelNames[0];
		$this->Controller->$model->recursive = - 1;

		$copyText = sprintf('- %s ( %s )', __('copy', true), date('Y-m-d'));

		$saves = 0;
		foreach($ids as $id) {
			$record = $this->Controller->$model->read(null, $id);
			unset($record[$model]['id']);

			if ($record[$model][$this->Controller->$model->displayField] != $this->Controller->$model->primaryKey) {
				$record[$model][$this->Controller->$model->displayField] = $record[$model][$this->Controller->$model->displayField] . $copyText;
			}

			$record[$model]['active'] = 0;
			unset( $record[$model]['created'] );
			unset( $record[$model]['modified'] );
			unset( $record[$model]['lft'] );
			unset( $record[$model]['rght'] );

			$this->Controller->$model->create();

			if ($this->Controller->$model->save($record)) {
				$saves++;
			} ;
		}

		if ($saves) {
			$this->Controller->Session->setFlash(__($saves . ' copies of ' . $model . ' was made', true));
			$this->Controller->redirect($this->Controller->referer());
		}

		$this->Controller->Session->setFlash(__('No copies could be made.', true));
		$this->Controller->redirect($this->Controller->referer());
	}

	/**
	* Generic action.
	*
	* This method handles the actions like add and edit. If there is no ids or
	* there is no id in the array it will redirect to the action without passing
	* an id.
	*
	* @param string $action the action to redirect to.
	* @param int $id the id of the record that is selected.
	*/
	function generic($action = 'add', $ids) {
		if (!$ids || !isset($ids[0])) {
			$this->Controller->redirect(array('action' => $action));
		}

		$this->Controller->redirect(array('action' => $action, $ids[0]));
	}

	/**
	* Global Unlock.
	*
	* Finds all tables in the app that have locked fields and unlocks them.
	*/
	function unlock($null = null) {
		$tables = $this->Controller->_getLockableTables();
		$this->db = ConnectionManager::getDataSource('default');

		$status = true;

		foreach($tables as $table){
			$status = $status && $this->db->query('UPDATE `'.$table['table'].'` SET `locked` = 0, `locked_by` = null, `locked_since` = null;');
		}
		if ($status) {
			$this->Controller->Session->setFlash(__('All records unlocked', true));
		}
		else{
			$this->Controller->Session->setFlash(__('There was a problem unlocking some records', true));
		}
		$this->Controller->redirect($this->Controller->referer());
	}
}