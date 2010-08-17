<?php
	/**
	 *
	 *
	 */
	class LayoutsController extends CmsAppController{
		public $name = 'Layouts';

		/**
		 * Helpers.
		 *
		 * @access public
		 * @var array
		 */
		public $helpers = array('Filter.Filter');

		public function admin_index() {
			$this->Layout->recursive = 1;
			$layouts = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name'
			);

			$this->set(compact('layouts','filterOptions'));
		}

		public function admin_add() {
			if (!empty($this->data)) {
				$this->Layout->create();
				if ($this->Layout->saveAll($this->data)) {
					$this->Session->setFlash(__('The layout has been saved', true));
					$this->redirect(array('action' => 'index'));
				}else {
					$this->Session->setFlash(__('The layout could not be saved. Please, try again.', true));
				}
			}
		}

		public function admin_edit($id = null) {
			if (!$id && empty($this->data)) {
				$this->Session->setFlash(__('Invalid layout', true));
				$this->redirect(array('action' => 'index'));
			}
			if (!empty($this->data)) {
				if ($this->Layout->save($this->data)) {
					$this->Session->setFlash(__('The layout has been saved', true));
					$this->redirect(array('action' => 'index'));
				}else {
					$this->Session->setFlash(__('The layout could not be saved. Please, try again.', true));
				}
			}
			if (empty($this->data)) {
				$this->data = $this->Layout->lock(null, $id);
				if ($this->data === false) {
					$this->Session->setFlash(__('The layout item is currently locked', true));
					$this->redirect($this->referer());
				}
			}
		}
	}