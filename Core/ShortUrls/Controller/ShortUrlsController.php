<?php
class ShortUrlsController extends ShortUrlsAppController {
/**
 * view a short url (redirect to it)
 *
 * This uses the view method to take advantage of the automatic view counter
 */
	public function view() {
		if(!isset($this->request->params['pass'][0])) {
			$this->redirect($this->referer());
		}

		$url = $this->ShortUrl->getUrl($this->request->params['pass'][0]);

		if(empty($url)) {
			$this->notice(
				__('Page not found'),
				array(
					'redirect' => true
				)
			);
		}

		$this->redirect($url);
	}

/**
 * preview a short url before redirecting to it
 */
	public function preview() {
		if(!isset($this->request->params['pass'][0])) {
			$this->redirect($this->referer());
		}

		$url = $this->ShortUrl->getUrl($this->request->params['pass'][0]);

		if(empty($url)) {
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

/**
 * manage the short urls in the app
 */
	public function admin_index() {
		$this->Paginator->settings = array(
			'order' => array(
				$this->modelClass . '.modified' => 'desc'
			)
		);

		$shortUrls = $this->Paginator->paginate(null, $this->Filter->filter);

		$filterOptions = $this->Filter->filterOptions;
		$filterOptions['fields'] = array(
			'url'
		);

		$this->set(compact('shortUrls', 'filterOptions'));
	}
}