<?php
	/**
	 * Comment Template.
	 *
	 * @todo Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package sort
	 * @subpackage sort.comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class RoutesController extends RoutesAppController {
		public $listThemes = array(0 => 'Default');

		public function beforeFilter() {
			parent::beforeFilter();

			$this->listThemes = array(
				0 => __('Default')
			) + $this->Route->Theme->find('list');
			return true;
		}

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
				'theme_id' => array(null => __('All')) + $this->Route->Theme->find('list'),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('routes', 'filterOptions'));
		}

		public function admin_add() {
			parent::admin_add();

			$this->set('plugins', $this->Route->getPlugins());
			$this->set('themes', $this->listThemes);
		}

		public function admin_edit($id = null) {
			parent::admin_edit($id);

			$plugins = $this->Route->getPlugins();
			$controllers = $this->Route->getControllers($this->request->data['Route']['plugin']);
			$actions = $this->Route->getActions($this->request->data['Route']['plugin'], $this->request->data['Route']['controller']);
			$themes = $this->listThemes;

			$this->set(compact('plugins', 'controllers', 'actions', 'themes'));
		}
	}