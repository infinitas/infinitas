<?php
	/**
	 * Mass action component
	 *
	 * This handles all the different form actions, especialy delete / copy /
	 * toggle where you need to manipulate many records at a time.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package libs
	 * @subpackage libs.controllers.components.mass_action
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 * @author dakota
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class MassActionComponent extends Object {
		var $name = 'MassAction';

		/**
		* Controllers initialize function.
		*/
		function initialize(&$controller, $settings = array()) {
			$this->Controller = &$controller;
			$settings = array_merge(array(), (array)$settings);
		}

		/**
		* Get submitted ids.
		*
		* Checks the form data and returns an array of all the ids found for that
		* model.
		*
		* @param string $massAction the action to preform
		* @param array $data the form data
		*
		* @return array $ids the array of ids for the model that was selected.
		*/
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

		/**
		* Get the action to preform.
		*
		* Gets the action that was selected from the form.
		*
		* @param array $form the data from the form submition $this->params['form']
		*
		* @return string the action selected, or redirect to referer if no action found.
		*/
		function getAction($form) {
			if (isset($form['action'])) {
				return $form['action'];
			}

			$this->Controller->Session->setFlash(__('I dont know what to do.', true));
			$this->Controller->redirect($this->Controller->referer());
		}

		/**
		* Filter records.
		*
		* Checks the data posted from the form and redirects to the url with the params
		* for the filter component to catch.
		*/
		function filter($null = null) {
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

		/**
		* Delete records.
		*
		* Take the array of ids and checks that the deletion was confirmed. if it is
		* they will be sent for delete processing (could be soft|hard delete).
		*
		* If there was no javascript confirmation a page is displayed with the confirmation
		*/
		function delete($ids) {
			$model = $this->Controller->modelClass;

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

		/**
		* Handle delete requests.
		*
		* Takes the ids and if the model is using the soft delete behavior it will
		* stick them in the trash (set a delete flag on the record) or it will do a hard
		* delete.
		*
		* @param array $ids the ids to delete.
		*/
		function __handleDeletes($ids) {
			$model = $this->Controller->modelClass;

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

			$prettyModelName = low(Inflector::humanize(Inflector::underscore(Inflector::pluralize($modelName))));
			
			if($result == true) {
				$this->Controller->Session->setFlash(__('The ' . $prettyModelName . ' have been', true) . ' ' . $message);
			}

			else {
				$this->Controller->Session->setFlash(__('The ' . $prettyModelName . ' could not be', true) . ' ' . $message);
			}

			$this->Controller->redirect($this->Controller->data['Confirm']['referer']);
		}

		/**
		 * toggle records.
		 *
		 * Takes the array of ids that are passed in and toggles them. If they are active
		 * they will be inactive and inactive records will be active.
		 *
		 * @param array $ids array of ids.
		 */
		function toggle($ids) {
			$model = $this->Controller->modelClass;
			$this->Controller->$model->recursive = - 1;
			$ids = $ids + array(0);

			$prettyModelName = low(Inflector::humanize(Inflector::underscore(Inflector::pluralize($modelName))));
			
			if ($this->Controller->$model->updateAll(
					array($model . '.active' => '1 - `' . $model . '`.`active`'),
						array($model . '.id IN(' . implode(',', $ids) . ')')
						)
					) {
				$this->Controller->Session->setFlash(__('The ' . $prettyModelName . ' were toggled', true));
				$this->Controller->redirect($this->Controller->referer());
			}

			$this->Controller->Session->setFlash(__('The ' . $prettyModelName . ' could not be toggled', true));
			$this->Controller->redirect($this->Controller->referer());
		}

		/**
		* Copy a record.
		*
		* Takes a record id and reads it from the database. Then unset some data
		* that is not needed like id and created times and saves the new record.
		*
		* @todo open add page that is filled out.
		*
		* @param array $ids array of ids.
		*/
		function copy($ids) {
			$model = $this->Controller->modelClass;
			$this->Controller->$model->recursive = - 1;

			$copyText = sprintf('- %s ( %s )', __('copy', true), date('Y-m-d'));
			
			$prettyModelName = low(Inflector::humanize(Inflector::underscore($modelName)));
			
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
				$this->Controller->Session->setFlash(__($saves . ' copies of ' . $prettyModelName . ' was made', true));
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