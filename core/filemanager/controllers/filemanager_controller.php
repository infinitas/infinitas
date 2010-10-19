<?php
	/**
	*/
	class FilemanagerController extends CoreAppController {
		public $name = 'Filemanager';

		public $uses = array('Filemanager.Files', 'Filemanager.Folders');

		public function admin_index() {
			$path = '/';
			if(!empty($this->params['pass'])){
				$path = implode('/', $this->params['pass']);
			}

			$this->Folders->recursive = 2;
			$folders = $this->Folders->find(
				'all',
				array(
					'fields' => array(
					),
					'conditions' => array(
						'path' => $path
					),
					'order' => array(
						'name' => 'ASC'
					)
				)
			);

			$this->Files->recursive = 2;
			$files = $this->Files->find(
				'all',
				array(
					'fields' => array(
						),
					'conditions' => array(
						'path' => $path
						),
					'order' => array(
						'name' => 'ASC'
						)
					)
				);

			$this->set(compact('files', 'folders'));
		}

		public function admin_view() {
			if(!empty($this->params['pass'])){
				$path = implode('/', $this->params['pass']);
			}

			if (!$path || !is_file(APP.$path)) {
				$this->notice(
					__('Please select a file first', true),
					array(
						'level' => 'error',
						'redirect' => true
					)
				);
			}

			$this->set('path', APP.$path);
		}

		public function admin_download($file = null) {
			if (!$file) {
				$this->notice(
					__('Please select a file first', true),
					array(
						'level' => 'error',
						'redirect' => true
					)
				);
			}
			//  @todo mediaViews
		}

		public function admin_delete($file = null) {
			if (!$file) {
				$this->Infinitas->noticeInvalidRecord();
			}

			if ($this->FileManager->delete($file)) {
				$this->Infinitas->noticeDeleted();
			}

			$this->Infinitas->noticeNotDeleted();
		}
	}