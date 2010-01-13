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
	 * @link http://www.dogmatic.co.za
	 * @package sort
	 * @subpackage sort.comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class ThemesController extends ManagementAppController{
		var $name = 'Themes';

		function beforeFilter() {
			parent::beforeFilter();
		}

		function admin_index() {
			$themes = $this->paginate();
			$this->set(compact('themes'));
		}

		function admin_add() {
			if (!empty($this->data)) {
				$this->Theme->create();
				if ($this->Theme->saveAll($this->data)) {
					$this->Session->setFlash('Your theme has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}

		function admin_edit($id = null) {
			if (!$id) {
				$this->Session->setFlash(__('That theme could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Theme->save($this->data)) {
					$this->Session->setFlash(__('Your theme has been saved.', true));
					$this->redirect(array('action' => 'index'));
				}

				$this->Session->setFlash(__('Your theme could not be saved.', true));
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Theme->read(null, $id);
			}
		}
	}
?>