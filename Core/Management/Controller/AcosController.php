<?php
	class AcosController extends ManagementAppController {
		public $name = 'Acos';

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
				$this->Infinitas->noticeInvalidRecord();
			}
			
			$this->set('aco', $this->Aco->read(null, $id));
		}

		function admin_add() {
			parent::admin_add();

			$parentAcos = $this->Aco->ParentAco->find('list');
			$aros = $this->Aco->Aro->find('list');
			$this->set(compact('parentAcos', 'aros'));
		}

		function admin_edit($id = null) {
			parent::admin_edit($id);

			$parentAcos = $this->Aco->ParentAco->find('list');
			$aros = $this->Aco->Aro->find('list');
			$this->set(compact('parentAcos', 'aros'));
		}
	}