<?php
class AcosController extends ManagementAppController {
	var $name = 'Acos';

	function admin_index() {
		$acos = $this->Aco->find(
			'all',
			array(
				'fields' => array(
					'Aco.id',
					'Aco.parent_id',
					'Aco.model',
					'Aco.foreign_key',
					'Aco.alias',
					'Aco.lft',
					'Aco.rght',
				),
				'conditions' => array(
					//'Aco.rght = Aco.lft + 1'
					'Aco.parent_id > 1'
				),
				'contain' => array(
					'ParentAco' => array(
						'fields' => array(
							'ParentAco.id',
							'ParentAco.alias'
						)
					)
				)
			)
		);

		$this->set(compact('acos'));
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid aco', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('aco', $this->Aco->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Aco->create();
			if ($this->Aco->save($this->data)) {
				$this->Session->setFlash(__('The aco has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The aco could not be saved. Please, try again.', true));
			}
		}
		$parentAcos = $this->Aco->ParentAco->find('list');
		$aros = $this->Aco->Aro->find('list');
		$this->set(compact('parentAcos', 'aros'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid aco', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Aco->save($this->data)) {
				$this->Session->setFlash(__('The aco has been saved', true));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The aco could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Aco->read(null, $id);
		}
		$parentAcos = $this->Aco->ParentAco->find('list');
		$aros = $this->Aco->Aro->find('list');
		$this->set(compact('parentAcos', 'aros'));
	}
}