<?php
/**
 * ModulePositionsController
 *
 * @package Infinitas.Modules.Controller
 */

/**
 * ModulePositionsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Modules.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ModulePositionsController extends ModulesAppController {
/**
 * List available module positions
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array(
				'Module'
			)
		);
		$modules = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name'
		);

		$groups = $this->{$this->modelClass}->Module->Group->find('list');
		$modulePositions = $this->Paginator->paginate();
		$this->set(compact('modulePositions', 'groups', 'filterOptions'));
	}

}