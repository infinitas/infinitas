<?php
class PagesController extends ManagementAppController
{
	var $name = 'Pages';
	
	function admin_index()
	{
		$pages = $this->paginate(
			null,
			$this->Filter->filter
		);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'name',
			'type',
			'active' => (array)Configure::read('CORE.active_options')
		);
		
		$path = APP . str_replace(array('/', '\\'), DS, Configure::read('CORE.page_path'));
		$writable = is_writable($path);

		$this->set(compact('pages','filterOptions', 'writable', 'path'));	
	}
	
	function admin_edit($filename)
	{
		if (!$filename) {
			$this->Session->setFlash(__('That page could not be found', true), true);
			$this->redirect($this->referer());
		}

		if (!empty($this->data)) {
			if ($this->Page->save($this->data)) {
				$this->Session->setFlash(__('Your page has been saved.', true));
				$this->redirect(array('action' => 'index'));
			}

			$this->Session->setFlash(__('Your page could not be saved.', true));
		}

		if ($filename && empty($this->data)) {
			$this->data = $this->Page->read(null, $filename);
		}
	}
	
	function admin_add()
	{
		if (!empty($this->data)) {
			$this->data['Page']['file_name'] = low(Inflector::slug($this->data['Page']['name']));

			if ($this->Page->save($this->data)) {
				$this->Session->setFlash(__('Your page has been saved.', true));
				$this->redirect(array('action' => 'index'));
			}

			$this->Session->setFlash(__('Your page could not be saved.', true));
		}
	}	
}