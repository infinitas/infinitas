<?php
	/**
	 *
	 *
	 */
	class SiteMapsController extends WebmasterAppController{
		public $name = 'SiteMaps';

		public $uses = array();

		public function admin_index(){
			Configure::write('debug', 0);
			$this->helpers[] = 'Xml';

			$map = $this->admin_rebuild();
			$this->set('map', $map['urlset']);
		}

		public function admin_rebuild(){
			$siteMaps = $this->Event->trigger('siteMapRebuild');
			$map = array();
			foreach($siteMaps['siteMapRebuild'] as $plugin){
				foreach($plugin as $link){
					if(!isset($link['url'])){
						continue;
					}
					
					$map['urlset'][] = array(
						'url' => array(
							'loc' => $link['url'],
							'lastmod' => date('Y-m-d\Th:mP', strtotime(isset($link['last_modified']) ? $link['last_modified'] : Configure::read('Webmaster.last_modified'))),
							'changefreq' => isset($link['change_frequency']) ? $link['change_frequency'] : Configure::read('Webmaster.change_frequency'),
							'priority' => isset($link['priority']) ? $link['priority'] : Configure::read('Webmaster.priority')
						)
					);
				}
			}

			Cache::write('sitemap', $map, 'webmaster');

			return $map;
		}
	}