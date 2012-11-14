<?php
/**
 * SecurityEvents
 *
 * @package Infinitas.Security.Lib
 */

/**
 * SecurityEvents
 *
 * The events that can be triggered for the security plugin.
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Security.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class SecurityEvents extends AppEvents {
/**
 * Load the security component
 *
 * @param Event $Event The event being loaded
 *
 * @return array
 */
	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'Security.InfinitasSecurity'
		);
	}

}