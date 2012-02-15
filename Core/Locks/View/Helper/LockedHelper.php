<?php
	/**
	 * Show who locked what.
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.locks
	 * @subpackage Infinitas.locks.helpers.locked
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class LockedHelper extends InfinitasHelper {
		public $helpers = array(
			'Time', 'Html',
			'Libs.Image'
		);
		
		/**
		 * Create a locked icon.
		 *
		 * takes the data from a find and shows if it is locked and if so who by
		 * and when.
		 *
		 * @param array $row the row of data from the find
		 *
		 * @return sstring some html with the image
		 */
		public function display($row = array(), $model = 'Lock'){
			$row = array_filter($row[$model]);
			if(!empty($row['id'])) {
				return $this->Html->image(
					$this->Image->getRelativePath('status', 'locked'),
					array(
						'alt' => __d('locks', 'Locked'),
						'width' => '16px',
						'title' => sprintf(
							__d('locks', 'Locked :: This record was locked %s by %s'),
							$this->Time->timeAgoInWords($row['created']),
							$row['Locker']['username']
						)
					)
				);
			}
			
			return $this->Html->image(
				$this->Image->getRelativePath('status', 'not-locked'),
				array(
					'alt' => __d('locks', 'Not Locked'),
					'width' => '16px',
					'title' => __d('locks', 'Unlocked :: This record is not locked')
				)
			);
		}
	}