<?php
/**
 * AcosController
 *
 * @package Infinitas.Users.Controller
 */

/**
 * AcosController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Users.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
class AcosController extends ManagementAppController {
/**
 * List ACOs
 *
 * @return void
 */
	public function admin_index() {
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

/**
 * View ACOs
 *
 * @param string $id the ACO id
 *
 * @return void
 */
	public function admin_view($id = null) {
		if (!$id) {
			$this->notice('invalid');
		}

		$this->set('aco', $this->Aco->read(null, $id));
	}

/**
 * Add ACOs
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

		$parentAcos = $this->Aco->ParentAco->find('list');
		$aros = $this->Aco->Aro->find('list');
		$this->set(compact('parentAcos', 'aros'));
	}

/**
 * Edit existing ACOs
 *
 * @param string $id the ACO id
 *
 * @return void
 */
	public function admin_edit($id = null) {
		parent::admin_edit($id);

		$parentAcos = $this->Aco->ParentAco->find('list');
		$aros = $this->Aco->Aro->find('list');
		$this->set(compact('parentAcos', 'aros'));
	}

}