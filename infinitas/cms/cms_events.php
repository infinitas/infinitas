<?php
	class CmsEvents {
		function onSetupCache(){
			Cache::config(
				'cms',
				array(
					'engine' => 'File',
					'duration' => 3600,
					'probability' => 100,
					'prefix' => '',
					'lock' => false,
					'serialize' => true,
					'path' => CACHE . 'cms'
				)
			);
		}

		function onSlugUrl(&$event, $data){
			switch(strtolower($data['type'])){
				case 'content':
					return array(					
						'plugin'     => 'cms',
						'controller' => 'contents',
						'action'     => 'view',
						'id'         => $data['data']['id'],
						'slug'       => $data['data']['slug'],
						'category'   => $data['data']['Category']['slug']
					);
					break;
			} // switch
		}
	}