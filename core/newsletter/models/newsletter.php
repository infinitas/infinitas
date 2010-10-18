<?php
	/**
	 * Newsletter Newsletter Model class file.
	 *
	 * This is the main model for Newsletter Newsletters. There are a number of
	 * methods for getting the counts of all posts, active posts, pending
	 * posts etc.  It extends {@see NewsletterAppModel} for some all round
	 * functionality. look at {@see NewsletterAppModel::afterSave} for an example
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package newsletter
	 * @subpackage newsletter.models.newsletter
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	class Newsletter extends NewsletterAppModel {
		public $name = 'Newsletter';

		/**
		 * always sort newsletters by the subject
		 */
		public $order = array(
			'Newsletter.subject' => 'asc'
		);

		/**
		 * For generating lists due to not being convention of name|title
		 */
		public $displayField = 'subject';

		public $hasAndBelongsToMany = array(
			'User' => array(
				'className' => 'Users.User',
				'joinTable' => 'newsletters_users',
				'foreignKey' => 'newsletter_id',
				'associationForeignKey' => 'user_id',
				'with' => 'Newsletter.NewslettersUser',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => ''
			)
		);

		public $belongsTo = array(
			'Campaign' => array(
				'className' => 'Newsletter.Campaign',
				'counterCache' => true,
			),
			'Newsletter.Template'
		);

		public function  __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->validate = array(
				'campaign_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the campaign this email belongs to', true)
					)
				),
				'from' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the from address', true)
					),
					'email' => array(
						'rule' => array( 'email', true ),
						'message' => __('Please enter a valid email addres', true)
					)
				),
				'reply_to' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the reply to email', true)
					),
					'email' => array(
						'rule' => array('email', true),
						'message' => __('Please enter a valid email addres', true)
					)
				),
				'subject' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the subject of this newsletter', true)
					)
				),
				'html' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the html version of your email', true)
					)
				),
				'text' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the text version of your email', true)
					)
				)
			);
		}
	}