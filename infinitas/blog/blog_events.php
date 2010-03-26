<?php
	class BlogEvents{
		function onSetupCache(){
			Cache::config(
				'blog',
				array(
					'engine' => 'File',
					'duration' => 3600,
					'probability' => 100,
					'prefix' => '',
					'lock' => false,
					'serialize' => true,
					'path' => CACHE . 'blog'
				)
			);
		}
	}