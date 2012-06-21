<?php
	/**
	 *
	 *
	 */
	class SiteMapsController extends WebmasterAppController {
		public $uses = array();

		public function index() {
			Configure::write('debug', 0);
			$this->helpers[] = 'Xml';

			$map = $this->__rebuild();
			$this->set('map', $map['urlset']);
		}

		private function __rebuild() {
			$siteMaps = $this->Event->trigger('siteMapRebuild');
			$map = array();
			foreach((array)$siteMaps['siteMapRebuild'] as $plugin) {
				foreach((array)$plugin as $link) {
					if(!isset($link['url'])) {
						continue;
					}

					$time = strtotime(isset($link['last_modified']) ? $link['last_modified'] : Configure::read('Webmaster.last_modified'));
					$lastModified = date('Y-m-d\Th:mP', $time);
					$changeFreq = isset($link['change_frequency']) ? $link['change_frequency'] : Configure::read('Webmaster.change_frequency');
					$priority = isset($link['priority']) ? $link['priority'] : Configure::read('Webmaster.priority');
					$map['urlset'][] = array(
						'url' => array(
							'loc' => $link['url'],
							'lastmod' => $lastModified,
							'changefreq' => $changeFreq,
							'priority' => $priority
						)
					);
				}
			}

			Cache::write('sitemap', $map, 'webmaster');

			return $map;
		}
	}