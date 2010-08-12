<?php
	class CmsEvents {
		function onSetupCache(){
			return array(
				'name' => 'cms',
				'config' => array(
					'duration' => 3600,
					'probability' => 100,
					'prefix' => 'cms.',
					'lock' => false,
					'serialize' => true
				)
			);
		}

		function onSlugUrl(&$event, $data){
			switch(strtolower($data['type'])){
				case 'contents':
					$url = array(
						'plugin'     => 'cms',
						'controller' => 'contents',
						'action'     => 'view',
						'id'         => $data['data']['Content']['id'],
						'slug'       => $data['data']['Content']['slug'],
						'category'   => isset($data['data']['Category']['slug']) ? $data['data']['Category']['slug'] : __('news-item',true)
					);
					break;

				default:
					echo 'Error: invalid url type';
					break;
			} // switch

			return $url;
		}
	}