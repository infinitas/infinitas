<?php
	/**
	 * Comment Template.
	 *
	 * @todo Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package sort
	 * @subpackage sort.comments
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 */

	class Campaign extends NewsletterAppModel {
		public $name = 'Campaign';

		public $lockable = true;

		public $order = array(
			'Campaign.name' => 'asc'
		);

		public $hasMany = array(
			'Newsletter.Newsletter'
		);

		public $belongsTo = array(
			'Newsletter.Template'
		);
	}