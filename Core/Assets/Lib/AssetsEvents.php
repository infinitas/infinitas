<?php
/**
 * Asset Compress helper
 *
 * @link http://infinitas-cms.org/infinitas_docs/Assets Infinitas Assets
 *
 * @package Infinitas.Assets.Lib
 */

/**
 * Assets events
 *
 * The events class for the Assets plugin
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org/infinitas_docs/Assets Infinitas Assets
 * @package Infinitas.Assets.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class AssetsEvents extends AppEvents {
/**
 * get helpers to load
 *
 * @param Event $Event The Event being triggered
 *
 * @return array
 */
	public function onRequireHelpersToLoad(Event $Event) {
		return array(
			'Assets.Compress'
		);
	}

/**
 * load javascript assets
 *
 * @param Event $Event The Event being triggered
 *
 * @return array
 */
	public function onRequireJavascriptToLoad(Event $Event) {
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
		if(isset($Event->Handler->params['admin']) && $Event->Handler->params['admin']) {
			$added = array(
				'Assets.3rd/date',
				'Assets.3rd/image_drop_down'
			);
		}

		return array_merge($return, $added);
	}
}