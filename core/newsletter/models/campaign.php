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

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the name of this campaign', true)
					),
					'isUnique' => array(
						'rule' => 'isUnique',
						'message' => __('There is already a campaign with that name', true)
					)
				),
				'template_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the default template for this campaign', true)
					)
				)
			);
		}
	}