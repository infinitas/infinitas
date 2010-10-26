<?php
	class ShortUrlsController extends ShortUrlsAppController{
		public $name = 'ShortUrls';

		public function view(){
			if(!isset($this->params['pass'][0])){
				$this->redirect($this->referer());
			}

			$url = $this->ShortUrl->getUrl($this->params['pass'][0]);

			if(empty($url)){
				$this->Session->setFlash(__('Page not found', true));
				$this->redirect($this->referer());
			}

			$this->redirect($url);
		}

		public function preview(){
			if(!isset($this->params['pass'][0])){
				$this->redirect($this->referer());
			}

			$url = $this->ShortUrl->getUrl($this->params['pass'][0]);

			if(empty($url)){
				$this->Session->setFlash(__('Page not found', true));
				$this->redirect($this->referer());
			}

			$shortUrl = $this->ShortUrl->find(
				'first',
				array(
					'conditions' => array(
						'ShortUrl.url' => $url
					)
				)
			);

			$this->set(compact('shortUrl'));
		}

		public function admin_index(){
			$shortUrls = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'url'
			);

			$this->set(compact('shortUrls', 'filterOptions'));
		}
	}