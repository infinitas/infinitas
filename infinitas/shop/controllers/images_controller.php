<?php
	class ImagesController extends ShopAppController{
		var $name = 'Images';

		var $helpers = array(
			'Filter.Filter'
		);


		function admin_index(){
			$this->paginate = array(
				'fields' => array(
					'Image.id',
					'Image.image',
					'Image.ext',
					'Image.width',
					'Image.height',
				),
				'contain' => false
			);

			$images = $this->paginate(
				null,
				$this->Filter->filter
			);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'image',
				'ext',
				'width',
				'height'
			);
			$this->set(compact('images','filterOptions'));
		}

		function admin_add(){
			if (!empty($this->data)) {
				$this->Image->create();
				if ($this->Image->saveAll($this->data)) {
					$this->Session->setFlash('Your image has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}
		}

		function admin_edit($id = null){
			if (!$id) {
				$this->Session->setFlash(__('That image could not be found', true), true);
				$this->redirect($this->referer());
			}

			if (!empty($this->data)) {
				if ($this->Image->saveAll($this->data)) {
					$this->Session->setFlash('Your image has been saved.');
					$this->redirect(array('action' => 'index'));
				}
			}

			if ($id && empty($this->data)) {
				$this->data = $this->Image->read(null, $id);
			}
		}
	}