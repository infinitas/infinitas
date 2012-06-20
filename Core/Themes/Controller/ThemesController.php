<?php
	/**
	 * Themes controller for managing the themes in infinitas
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

	class ThemesController extends ThemesAppController {
		public function admin_index() {
			$this->Theme->recursive = 1;
			$themes = $this->Paginator->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name' => $this->Theme->find('list'),
				'licence',
				'author',
				'core' => Configure::read('CORE.core_options'),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('themes', 'filterOptions'));
		}

		public function admin_add() {
			parent::admin_add();
			
			if(!$themes = $this->Theme->notInstalled()) {
				$this->notice(
					__('You do not have any themes to add'),
					array(
						'level' => 'warning',
						'redirect' => true
					)
				);
			}
			
			$this->set(compact('themes'));
		}

		public function admin_edit($id) {
			parent::admin_edit($id);
			$themes = $this->Theme->notInstalled();
			$themes[$this->request->data['Theme']['name']] = $this->request->data['Theme']['name'];
			$this->set(compact('themes'));
		}
		
		public function frontend_css() {
			$this->layout = 'ajax';
			$this->response->type('css');
			$css = $this->Event->trigger('requireCssToLoad');
			$this->set('css', array_filter(array_values(Set::flatten($css))));
		}

		/**
		 * Mass toggle action.
		 *
		 * This overwrites the default toggle action so that other themes can
		 * be deactivated first as you should only have one active at a time.
		 *
		 * @var array $ids the id of the theme to toggle
		 */
		public function __massActionToggle($ids) {
			if (count($ids) > 1) {
				$this->notice(
					__('Please select only one theme to be active'),
					array(
						'level' => 'warning',
						'redirect' => true
					)
				);
			}

			if ($this->Theme->deactivateAll()) {
				return $this->MassAction->toggle($ids);
			}

			$this->notice(
				__('There was a problem deactivating the other theme'),
				array(
					'level' => 'error',
					'redirect' => true
				)
			);
		}
		
		/**
		 * @brief redirect to the installer to add a new theme.
		 * 
		 * @param null $ids not used
		 */
		public function __massActionInstall($ids) {
			$this->redirect(
				array(
					'plugin' => 'installer',
					'controller' => 'plugins',
					'action' => 'install'
				)
			);
		}
	}