<?php
/**
 * the Events file for the Assets plugin
 *
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org
 * @package Assets.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author dogmatic69
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

class AssetsEvents extends AppEvents {
/**
 * get helpers to load
 *
 * @param Event $event The Event being triggered
 *
 * @return array
 */
	public function onRequireHelpersToLoad(Event $event) {
		return array(
			'Assets.Compress'
		);
	}

/**
 * load javascript assets
 *
 * @param Event $event The Event being triggered
 *
 * @return array
 */
	public function onRequireJavascriptToLoad(Event $event) {
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

		$added = array(
			'Assets.3rd/rater',
			'Assets.3rd/moving_boxes'
		);
		if(isset($event->Handler->params['admin']) && $event->Handler->params['admin']) {
			$added = array(
				'Assets.3rd/date',
				'Assets.3rd/image_drop_down'
			);
		}

		return array_merge($return, $added);
	}
}