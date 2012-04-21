<?php
	class GlobalSearchController extends ContentsAppController {
		public $uses = array(
			'Contents.GlobalContent'
		);
		
		public function search($term = null) {
			if(!empty($this->data[$this->modelClass]['search'])) {
				$url = array(
					'action' => 'search',
					Sanitize::paranoid($this->data[$this->modelClass]['search']),
					'global_category_id' => !empty($this->data[$this->modelClass]['global_category_id']) ? $this->data[$this->modelClass]['global_category_id'] : null
				);
				
				$this->redirect($url);
			}
			
			$category = !empty($this->request->params['named']['global_category_id']) ? $this->request->params['named']['global_category_id'] : null;
			try {
				$this->Paginator->settings = array('search', Sanitize::paranoid($term), $category);
				$this->set('search', $this->Paginator->paginate());
			}
			
			catch(Exception $e) {
				$this->notice(
					__d('contents', $e->getMessage()),
					array(
						'redirect' => false,
						'level' => 'warning'
					)
				);
			}
			
			$this->request->data[$this->modelClass]['global_category_id'] = $category;
			
			$this->set(
				'globalCategories', 
				array_merge(array(null => __d('contents', 'All')), $this->{$this->modelClass}->find('categoryList'))
			);
		}
	}