<?php
/**
*/
class FileManagerController extends AppController {
	var $name = 'FileManager';

	var $uses = array('Filemanager.Files', 'Filemanager.Folders');

	var $helpers = array(
		'Libs.Image',
		'Number',
		'Management.Core',
		'Filter.Filter'
	);

	function admin_index() {
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

	function admin_view() {
		if(!empty($this->params['pass'])){
			$path = implode('/', $this->params['pass']);
		}

		if (!$path || !is_file(APP.$path)) {
			$this->Session->setFlash(__('Please select a file first', true));
			$this->redirect($this->referer());
		}

		$this->set('path', APP.$path);
	}

	function admin_download($file = null) {
		if (!$file) {
			$this->Session->setFlash(__('Please select a file first', true));
			$this->redirect($this->referer());
		}
		//  @todo mediaViews
	}

	function admin_delete($file = null) {
		if (!$file) {
			$this->Session->setFlash(__('Please select a file first', true));
			$this->redirect($this->referer());
		}

		if ($this->FileManager->delete($file)) {
			$this->Session->setFlash(__('File deleted', true));
			$this->redirect($this->referer());
		}

		$this->Session->setFlash(__('File could not be deleted', true));
		$this->redirect($this->referer());
	}
}

?>