<?php
/**
 * RoutesController
 *
 * @package Infinitas.Routes.Controller
 */

/**
 * RoutesController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Routes.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class RoutesController extends RoutesAppController {
/**
 * List of themes
 *
 * @var array
 */
	public $listThemes = array(0 => 'Default');

/**
 * BeforeFilter callback
 *
 * Get a list of themes
 *
 * @return boolean
 */
	public function beforeFilter() {
		parent::beforeFilter();

		$this->listThemes = array(
			0 => __d('routes', 'Default')
		) + $this->Route->Theme->find('list');
		return true;
	}

/**
 * list the routes for the site
 *
 * @return void
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'contain' => array(
				'Theme'
			)
		);

		$routes = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'url',
			'plugin' => $this->Route->getPlugins(),
			'theme_id' => array(null => __d('routes', 'All')) + $this->Route->Theme->find('list'),
			'active' => Configure::read('CORE.active_options')
		);

		$this->set(compact('routes', 'filterOptions'));
	}

/**
 * add a new route
 *
 * @return void
 */
	public function admin_add() {
		parent::admin_add();

		$plugins = $this->Route->getPlugins();
		$themes = $this->listThemes;
		$this->set(compact('plugins', 'themes'));
	}

/**
 * edit an existing route
 *
 * @param stirng $id the id of the route being edited
 *
 * @return void
 */
	public function admin_edit($id = null) {
		parent::admin_edit($id);

		$plugins = $this->Route->getPlugins();
		try {
			$controllers = $this->Route->getControllers($this->request->data['Route']['plugin']);
			$actions = $this->Route->getActions($this->request->data['Route']['plugin'], $this->request->data['Route']['controller']);
		} catch (Exception $e) {
			$this->notice($e->getMessage(), array(
				'level' => 'warning'
			));
		}
		$themes = $this->listThemes;

		$this->set(compact('plugins', 'controllers', 'actions', 'themes'));
	}

}