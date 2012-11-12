<?php
/**
 * Short Description / title.
 *
 * Overview of what the file does. About a paragraph or two
 *

 *
 * @filesource
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Core.Data.Lib
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class DataEvents extends AppEvents {
	public function onSetupExtensions() {
		return array(
			'csv'
		);
	}

	public function onAttachBehaviors($event = null) {
		if($event->Handler->shouldAutoAttachBehavior()) {
			$event->Handler->Behaviors->attach('Data.BigData');
		}
	}
}