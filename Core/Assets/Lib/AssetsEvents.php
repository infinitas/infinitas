<?php
	class AssetsEvents extends AppEvents {
		public function onRequireHelpersToLoad($event = null) {
			return array(
				'Assets.Compress'
			);
		}

		public function onRequireJavascriptToLoad($event, $data = null) {
			$return = array(
				'Assets.3rd/jquery',
				'Assets.3rd/jquery_ui',
				'Assets.3rd/metadata',
				'Assets.infinitas',
				'Assets.libs/core',
				'Assets.libs/form',
				'Assets.libs/html',
				'Assets.libs/number'
			);
			
			if(isset($event->Handler->params['admin']) && $event->Handler->params['admin']){
				$return[] = 'Assets.3rd/date';
				$return[] = 'Assets.3rd/image_drop_down';
			}
			
			else{
				$return[] = 'Assets.3rd/rater';
				$return[] = 'Assets.3rd/moving_boxes';
			}

			return $return;
		}
	}