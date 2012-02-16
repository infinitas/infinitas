<?php
	class TrashController extends TrashAppController {
		public function beforeFilter(){
			parent::beforeFilter();
			if(isset($this->request->params['form']['action']) && $this->request->params['form']['action'] == 'cancel'){
				unset($this->request->params['form']['action']);
				$this->redirect(array_merge(array('action' => 'list_items'), $this->request->params['named']));
			}
			return true;
		}

		/**
		 * List all table with deleted in the schema
		 */
		public function admin_index(){
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

		public function __massActionRestore($ids) {
			if($this->Trash->restore($ids)) {
				$this->notice(
					__('The items have been restored'),
					array(
						'redirect' => true
					)
				);
			}
			else {
				$this->notice(
					__('The items could not be restored'),
					array(
						'level' => 'error',
						'redirect' => true
					)
				);
			}
		}
	}