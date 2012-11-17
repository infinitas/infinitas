<?php
/**
 * DataEvents
 *
 * @package Infinitas.Data.Lib
 */

/**
 * DataEvents
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Data.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class DataEvents extends AppEvents {
/**
 * Configure csv extension parsing
 *
 * @return array
 */
	public function onSetupExtensions(Event $Event) {
		return array(
			'csv'
		);
	}

/**
 * Attach behaviors for data
 *
 * @param Event $Event
 */
	public function onAttachBehaviors(Event $Event) {
		if($Event->Handler->shouldAutoAttachBehavior()) {
			$Event->Handler->Behaviors->attach('Data.BigData');
		}
	}

}