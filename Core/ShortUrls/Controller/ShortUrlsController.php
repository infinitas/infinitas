<?php
	class ShortUrlsController extends ShortUrlsAppController {
		public function view(){
			if(!isset($this->request->params['pass'][0])){
				$this->redirect($this->referer());
			}

			$url = $this->ShortUrl->getUrl($this->request->params['pass'][0]);

			if(empty($url)){
				$this->notice(
					__('Page not found'),
					array(
						'redirect' => true
					)
				);
			}

			$this->redirect($url);
		}

		public function preview(){
			if(!isset($this->request->params['pass'][0])){
				$this->redirect($this->referer());
			}

			$url = $this->ShortUrl->getUrl($this->request->params['pass'][0]);

			if(empty($url)){
				$this->notice(
					__('Page not found'),
					array(
						'redirect' => true
					)
				);
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
			$shortUrls = $this->Paginator->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'url'
			);

			$this->set(compact('shortUrls', 'filterOptions'));
		}
	}