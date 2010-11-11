<?php
	class TrashController extends TrashAppController {
		var $name = 'Trash';

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
			$this->paginate = array(
				'contain' => array(
					'User'
				)
			);			
			$trashed = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'type',
				'deleted_by' => $this->Trash->User->find('list')
			);

			$this->set(compact('trashed', 'filterOptions'));
		}

		function __massActionRestore($ids) {
			if($this->Trash->restore($ids)) {
				$this->notice(
					__('The items have been restored', true),
					array(
						'redirect' => true
					)
				);
			}
			else {
				$this->notice(
					__('The items could not be restored', true),
					array(
						'level' => 'error',
						'redirect' => true
					)
				);
			}
		}
	}