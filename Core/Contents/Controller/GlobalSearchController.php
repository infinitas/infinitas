<?php
	class GlobalSearchController extends ContentsAppController {
		public $uses = array(
			'Contents.GlobalContent'
		);
		
		public function search($term = null) {
			if(!empty($this->data[$this->modelClass]['search'])) {
				$this->redirect(
					array(
						'action' => 'search',
						$this->data[$this->modelClass]['search']
					)
				);
			}
			
			try {
				$this->Paginator->settings = array('search', $term);
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
			
			$this->set(
				'globalCategories', 
				array_merge(array(null => __d('contents', 'All')), $this->{$this->modelClass}->find('categoryList'))
			);
		}
	}