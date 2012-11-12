<?php
	/*
	 * Short Description / title.
	 *
	 * Overview of what the file does. About a paragraph or two
	 *

	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Core.Google.Lib
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
	 */

	final class GoogleEvents extends AppEvents {
		public function onRequireHelpersToLoad() {
			return array(
				'Google.Chart'
			);
		}
	}
