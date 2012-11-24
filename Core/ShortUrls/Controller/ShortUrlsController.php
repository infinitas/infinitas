<?php
/**
 * ShortUrlsController
 *
 * @package Infinitas.ShortUrls.Controller
 */

/**
 * ShortUrlsController
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.ShortUrls.Controller
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class ShortUrlsController extends ShortUrlsAppController {
/**
 * view a short url (redirect to it)
 *
 * This uses the view method to take advantage of the automatic view counter
 *
 * @return void
 */
	public function view() {
		if(!isset($this->request->params['pass'][0])) {
			$this->redirect($this->referer());
		}

		$url = $this->ShortUrl->getUrl($this->request->params['pass'][0]);

		if(empty($url)) {
			$this->notice(
				__d('short_urls', 'Page not found'),
				array(
					'redirect' => true
				)
			);
		}

		$this->redirect($url);
	}

/**
 * Preview a short url before redirecting to it
 *
 * @return void
 */
	public function preview() {
		if(!isset($this->request->params['pass'][0])) {
			$this->redirect($this->referer());
		}

		$url = $this->ShortUrl->getUrl($this->request->params['pass'][0]);

		if(empty($url)) {
			$this->notice(
				__d('short_urls', 'Page not found'),
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
 *
 * @return void
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