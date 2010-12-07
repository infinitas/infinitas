<?php

	class AssetsEvents extends AppEvents{
		public function onSetupConfig($event, $data = null) {
			Configure::load('assets.config');
		}

		public function onRequireHelpersToLoad($event = null) {
			return array(
				'Assets.Compress'
			);
		}

		public function onRequireJavascriptToLoad($event, $data = null) {
			$return = array(
				'/assets/js/3rd/jquery',
				'/assets/js/3rd/jquery_ui',
				'/assets/js/3rd/metadata',
				'/assets/js/infinitas',
				'/assets/js/libs/core',
				'/assets/js/libs/form',
				'/assets/js/libs/html',
				'/assets/js/libs/number'
			);
			
			if(isset($event->Handler->params['admin']) && $event->Handler->params['admin']){
				$return[] = '/assets/js/3rd/date';
				$return[] = '/assets/js/3rd/image_drop_down';
			}
			
			else{
				$return[] = '/assets/js/3rd/rater';
				$return[] = '/assets/js/3rd/moving_boxes';
			}

			return $return;
		}
	}