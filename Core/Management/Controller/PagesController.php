<?php
	/**
	 * Static Page Manager
	 *
	 * Creating and maintainig static pages
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package management
	 * @subpackage management.controllers.pages
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author dakota
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class PagesController extends ManagementAppController{
		var $name = 'Pages';

		function admin_index(){
			$pages = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'name',
				'type',
				'active' => (array)Configure::read('CORE.active_options')
			);

			$path = APP . str_replace(array('/', '\\'), DS, Configure::read('CORE.page_path'));
			$writable = is_writable($path);

			$this->set(compact('pages', 'filterOptions', 'writable', 'path'));
		}

		function admin_add(){
			if (!empty($this->request->data)){
				$this->request->data['Page']['file_name'] = strtolower(Inflector::slug($this->request->data['Page']['name']));

				if ($this->Page->save($this->request->data)){
					$this->Infinitas->noticeSaved();
				}

				$this->Infinitas->noticeNotSaved();
			}
		}

		function admin_edit($filename){
			if (!$filename){
				$this->Infinitas->noticeInvalidRecord();
			}

			if (!empty($this->request->data)){
				if ($this->Page->save($this->request->data)){
					$this->Infinitas->noticeSaved();
				}

				$this->Infinitas->noticeNotSaved();
			}

			if ($filename && empty($this->request->data)){
				$this->request->data = $this->Page->read(null, $filename);
			}
		}

		function __massGetIds($data) {
			if (in_array($this->__massGetAction($this->params['form']), array('add','filter'))) {
				return null;
			}

			$ids = array();
			foreach($data as $id => $selected) {
				if ($selected) {
					$ids[] = $selected['id'];
				}
			}

			if (empty($ids)) {
				$this->Infinitas->noticeInvalidRecord();
			}

			return $ids;
		}

		function __massActionDelete($ids) {
			$deleted = true;
			foreach($ids as $id){
				$deleted = $deleted && $this->Page->delete($id);
			}

			if ($delete) {
				$this->Infinitas->noticeDeleted();
			}
			else{
				$this->Infinitas->noticeNotDeleted();
			}
		}
	}