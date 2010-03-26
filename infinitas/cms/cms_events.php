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
	}