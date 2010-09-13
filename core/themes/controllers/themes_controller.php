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

	class ThemesController extends ThemesAppController{
		public $name = 'Themes';

		public function admin_index() {
			$this->Theme->recursive = 1;
			$themes = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'licence',
				'author',
				'core' => Configure::read('CORE.core_options'),
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('themes', 'filterOptions'));
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
				$this->Session->setFlash(__('Please select only one theme to be active', true));
				$this->redirect($this->referer());
			}

			if ($this->Theme->deactivateAll()) {
				return $this->MassAction->toggle($ids);
			}

			$this->Session->setFlash(__('There was a problem deactivating the other theme', true));
			$this->redirect($this->referer());
		}
	}