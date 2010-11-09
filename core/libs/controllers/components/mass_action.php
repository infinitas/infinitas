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
		public $name = 'MassAction';

		private $__modelName = null;

		private $__prettyModelName = null;

		/**
		 * Controllers initialize function.
		 */
		public function initialize(&$controller, $settings = array()) {
			$this->Controller = &$controller;
			$settings = array_merge(array(), (array)$settings);

			$this->__modelName = $this->Controller->modelClass;
			$this->__prettyModelName = prettyName($this->__modelName);
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
		public function getIds($massAction, $data) {
			if (in_array($massAction, array('add','filter'))) {
				return null;
			}

			$ids = array();
			foreach($data as $id => $selected) {
				if ((is_int($id) || strlen($id) == 36 || preg_match('/.*@.*/', base64_decode($id))) && $selected) {
					$ids[] = $id;
				}
			}

			if (empty($ids)) {
				$this->Controller->notice(
					__('Nothing was selected, please select something and try again.', true),
					array(
						'level' => 'warning',
						'redirect' => isset($this->Controller->data['Confirm']['referer']) 
							? $this->Controller->data['Confirm']['referer'] 
							: true
					)
				);
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
		public function getAction($form) {
			if (isset($form['action'])) {
				return $form['action'];
			}

			$this->Controller->notice(
				__('I dont know what to do.', true),
				array(
					'level' => 'error',
					'redirect' => true
				)
			);
		}

		/**
		 * Filter records.
		 *
		 * Checks the data posted from the form and redirects to the url with the params
		 * for the filter component to catch.
		 */
		public function filter($null = null) {
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
		public function delete($ids) {
			if ((isset($this->Controller->data['Confirm']['confirmed']) && $this->Controller->data['Confirm']['confirmed']) || (isset($this->Controller->{$this->__modelName}->noConfirm))) {
				if(method_exists($this->Controller, '__handleDeletes')) {
					$this->Controller->__handleDeletes($ids);
				}
				else {
					$this->__handleDeletes($ids);
				}
			}

			$referer = $this->Controller->referer();
			$rows = $this->Controller->{$this->__modelName}->find('list', array('conditions' => array($this->__modelName.'.id' => $ids)));

			$this->Controller->set('model', $this->__modelName);
			$this->Controller->set(compact('referer', 'rows'));
			$this->Controller->render('delete', null, App::pluginPath('libs').'views'.DS.'global'.DS.'delete.ctp');
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
		public function __handleDeletes($ids) {
			$conditions = array($this->__modelName . '.' . $this->Controller->{$this->__modelName}->primaryKey => $ids);
			$params = array();
			
			if($this->Controller->{$this->__modelName}->deleteAll($conditions, true, true) == true) {
				$params = array(
					'message' => sprintf(__('The %s have been deleted', true), $this->__prettyModelName)
				);
			}

			else {
				$params = array(
					'level' => 'error',
					'message' => sprintf(__('The %s could not be deleted', true), $this->__prettyModelName)
				);
			}

			$params['redirect'] = isset($this->Controller->data['Confirm']['referer'])
				? $this->Controller->data['Confirm']['referer']
				: true;

			$this->Controller->notice($params['message'], $params);
		}

		/**
		 * toggle records.
		 *
		 * Takes the array of ids that are passed in and toggles them. If they are active
		 * they will be inactive and inactive records will be active.
		 *
		 * @param array $ids array of ids.
		 */
		public function toggle($ids) {			
			$conditions = array($this->__modelName . '.id IN (' . implode(',', array_merge(array(0=>0), $ids)) . ')');
			$newValues = array(
				$this->__modelName . '.active' => '1 - `' . $this->__modelName . '`.`active`'
			);

			if($this->Controller->{$this->__modelName}->hasField('modified')){
				$newValues[$this->__modelName . '.modified'] = '\'' . date('Y-m-d H:m:s') . '\'';
			}

			// unbind things for the update. dont need all the models for this.
			$this->Controller->{$this->__modelName}->unbindModel(
				array(
					'belongsTo' => array_keys($this->Controller->{$this->__modelName}->belongsTo),
					'hasOne' => array_keys($this->Controller->{$this->__modelName}->hasOne)
				)
			);

			if ($this->Controller->{$this->__modelName}->updateAll($newValues, $conditions)) {
				$this->Controller->{$this->__modelName}->afterSave(false);

				$this->Controller->notice(
					sprintf(__('The %s were toggled', true), $this->__prettyModelName),
					array(
						'redirect' => true
					)
				);
			}

			$this->Controller->notice(
				sprintf(__('The %s could not be toggled', true), $this->__prettyModelName),
				array(
					'level' => 'error',
					'redirect' => true
				)
			);
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
		public function copy($ids) {
			$copyText = sprintf('- %s ( %s )', __('copy', true), date('Y-m-d'));

			$saves = 0;
			foreach($ids as $id) {
				$record = $this->Controller->{$this->__modelName}->read(null, $id);
				unset($record[$this->__modelName]['id']);

				if ($record[$this->__modelName][$this->Controller->{$this->__modelName}->displayField] != $this->Controller->{$this->__modelName}->primaryKey) {
					$record[$this->__modelName][$this->Controller->{$this->__modelName}->displayField] = $record[$this->__modelName][$this->Controller->{$this->__modelName}->displayField] . $copyText;
				}

				$record[$this->__modelName]['active'] = 0;
				unset($record[$this->__modelName]['created']);
				unset($record[$this->__modelName]['modified']);
				unset($record[$this->__modelName]['lft']);
				unset($record[$this->__modelName]['rght']);
				unset($record[$this->__modelName]['ordering']);
				unset($record[$this->__modelName]['order_id']);
				unset($record[$this->__modelName]['views']);

				/**
				 * unset anything fields that are countercache
				 */
				foreach($record[$this->__modelName] as $field => $value){
					if(strstr($field, '_count')){
						unset($record[$this->__modelName][$field]);
					}
				}

				$this->Controller->{$this->__modelName}->create();

				if ($this->Controller->{$this->__modelName}->save($record)) {
					$saves++;
				}
			}

			if ($saves) {
				$this->Controller->notice(
					sprintf(__('%s copies of %s were made', true), $saves, $this->__prettyModelName),
					array(
						'redirect' => true
					)
				);
			}

			$this->Controller->notice(
				sprintf(__('No copies of %s could be made', true), $this->__prettyModelName),
				array(
					'level' => 'error',
					'redirect' => true
				)
			);
		}

		/**
		 * Move records
		 *
		 * find out relations like belongsTo and habtm and send the ids to a view
		 * so you can easily move many items
		 *
		 * @param array $ids array of ids.
		 */
		public function move($ids) {
			if (isset($this->Controller->data['Move']['confirmed']) && $this->Controller->data['Move']['confirmed']) {
				if(method_exists($this->Controller, '__handleMove')) {
					$this->Controller->__handleMove($ids);
				}
				else {
					$this->__handleMove($ids);
				}
			}

			$referer = $this->Controller->referer();
			$rows = $this->Controller->{$this->__modelName}->find('all', array('conditions' => array($this->__modelName.'.id' => $ids), 'contain' => false));
			$model = $this->__modelName;

			$relations['belongsTo'] = array();
			if (isset($this->Controller->{$this->__modelName}->belongsTo)) {
				$relations['belongsTo'] = $this->Controller->{$this->__modelName}->belongsTo;

				foreach($relations['belongsTo'] as $alias => $belongsTo){
					switch($alias){
						case 'Locker':
							break;

						case 'Parent Post':
						case 'Parent':
								$_Model = ClassRegistry::init($this->Controller->plugin.'.'.$this->__modelName);

								if(in_array('Tree', $_Model->Behaviors->_attached)){
									$_Model->order = array();
									$this->Controller->set(strtolower(Inflector::pluralize($alias)), $_Model->generateTreeList());
								}
								else{
									$this->Controller->set(strtolower(Inflector::pluralize($alias)), $_Model->find('list'));
								}
							break;

						default:
							$_Model = ClassRegistry::init($this->Controller->plugin.'.'.$alias);
							$_Model->order = array();
							$this->Controller->set(strtolower(Inflector::pluralize($alias)), $_Model->find('list'));
							break;
					}
				}
			}

			$relations['hasAndBelongsToMany'] = array();
			if (isset($this->Controller->{$this->__modelName}->hasAndBelongsToMany)) {
				$relations['hasAndBelongsToMany'] = $this->Controller->{$this->__modelName}->hasAndBelongsToMany;

				foreach($relations['hasAndBelongsToMany'] as $alias => $belongsTo){
					$_Model = ClassRegistry::init($this->Controller->plugin.'.'.$alias);
					$this->Controller->set(strtolower($alias), $_Model->find('list'));
				}
			}

			$modelSetup['displayField'] = $this->Controller->{$this->__modelName}->displayField;
			$modelSetup['primaryKey'] = $this->Controller->{$this->__modelName}->primaryKey;
			$this->Controller->set(compact('referer', 'rows', 'model', 'modelSetup', 'relations'));
			$this->Controller->render('move', null, App::pluginPath('libs').'views'.DS.'global'.DS.'move.ctp');
		}

		/**
		* Handle move requests.
		*
		* get the id's passed and assign the new relations so the records are
		* moved.
		*
		* @param array $ids the ids to delete.
		*/
		private function __handleMove($ids) {
			$movedTo = $this->Controller->data['Move'];
			unset($movedTo['model']);
			unset($movedTo['confirmed']);
			unset($movedTo['referer']);

			$result = true;

			foreach ($ids as $id){
				$row = array('id' => $id);
				$_data = array_merge(array_filter($movedTo), $row);

				$_mn = $this->__modelName;
				foreach($_data as $key => $value){
					if(is_array($value)){
						$save[$key][$key] = $value;
					}
					else{
						$save[$_mn][$key] = $value;
					}
				}

				$data = $this->Controller->{$this->__modelName}->read(null, $id);
				unset($data[$this->__modelName]['lft']);
				unset($data[$this->__modelName]['rght']);
				$save[$this->__modelName] = array_merge($data[$this->__modelName], $save[$this->__modelName]);
				// @todo this is messing up trees, need to see why the lft and rght is not updating.
				$result = $result && $this->Controller->{$this->__modelName}->saveAll($save);
				unset($save);
			}

			if(in_array('Tree', $this->Controller->{$this->__modelName}->Behaviors->_attached)){
				//$this->Controller->{$this->__modelName}->recover('parent');
			}

			if($result == true) {
				$params = array(
					'message' => sprintf(__('The %s have been moved', true), $this->__prettyModelName)
				);
			}

			else {
				$params = array(
					'level' => 'warning',
					'message' => sprintf(__('Some of the %s could not be moved', true), $this->__prettyModelName)
				);
			}

			$params['redirect'] = $this->Controller->data['Move']['referer'];

			$this->Controller->notice($params['message'], $params);
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
		public function generic($action = 'add', $ids) {
			if (!$ids || !isset($ids[0])) {
				$this->Controller->redirect(array('action' => $action));
			}

			$this->Controller->redirect(array('action' => $action, $ids[0]));
		}
	}