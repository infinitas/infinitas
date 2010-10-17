<?php
	/**
	 * Events for the Infinitas Short Urls
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.short_urls
	 * @subpackage Infinitas.short_urls.events
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	 final class ShortUrlsEvents extends AppEvents{
		public function onSetupRoutes(){
			// preview
			Router::connect(
				'/s/p/*',
				array(
					'admin' => false,
					'prefix' =>false,
					'plugin' => 'short_urls',
					'controller' => 'short_urls',
					'action' => 'preview'
				)
			);
			
			// redirect
			Router::connect(
				'/s/*',
				array(
					'admin' => false,
					'plugin' => 'short_urls',
					'controller' => 'short_urls',
					'action' => 'view'
				)
			);
		}

		public function onShortenUrl($event, $url){
			
		}

		public function onSlugUrl($event, $data){
			$data['type'] = isset($data['type']) ? $data['type'] : '';
			switch($data['type']){
				case 'preview':
					return array(
						'admin' => false,
						'plugin' => 'short_urls',
						'controller' => 'short_urls',
						'action' => 'preview',
						$data['code']
					);
					break;

				default:
					return array(
						'admin' => false,
						'plugin' => 'short_urls',
						'controller' => 'short_urls',
						'action' => 'view',
						$data['code']
					);
					break;
			}
		}

		/**
		 * event to create short urls from anywere in the code. show a preview 
		 * link by setting type => preview or leave type out type to go direct to
		 * the url
		 * 
		 * @param $event the event object
		 * @param $data array of type and url
		 * @return array a cake url array for the short url that was created
		 */
		public function onGetShortUrl($event, $data){
			$data['code'] = ClassRegistry::init('ShortUrls.ShortUrl')->shorten($data['url']);
			if(!$data['code']){
				$this->cakeError('error404', __('That url seems invalid', true));
			}

			return $this->onSlugUrl($event, $data);
		}
	}