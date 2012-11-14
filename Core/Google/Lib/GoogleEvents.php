<?php
/**
 * GoogleEvents
 *
 * @package Infinitas.Google.Lib
 */

/**
 * GoogleEvents
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Google.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class GoogleEvents extends AppEvents {
/**
 * Load google helpers
 *
 * @return array
 */
	public function onRequireHelpersToLoad() {
		return array(
			'Google.Chart'
		);
	}

}