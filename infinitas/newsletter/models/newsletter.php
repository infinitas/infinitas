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
	var $name = 'Newsletter';

	/**
	* always sort newsletters by the subject
	*/
	var $order = array(
		'Newsletter.subject' => 'ASC'
		);

	/**
	* For generating lists due to not being convention of name|title
	*/
	var $displayField = 'subject';

	var $validate = array(
		'campaign_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please select the campaign this email belongs to'
				)
			),
		'from' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the from address'
				),
			'email' => array(
				'rule' => array( 'email', true ),
				'message' => 'Please enter a valid email addres'
				)
			),
		'reply_to' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the reply to email'
				),
			'email' => array(
				'rule' => array('email', true),
				'message' => 'Please enter a valid email addres'
				)
			),
		'html' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the html version of your email'
				)
			),
		'text' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter the text version of your email'
				)
			)
		);

	var $hasAndBelongsToMany = array(
		'User' =>
		array(
			'className' => 'Management.User',
			'joinTable' => 'newsletter_newsletters_users',
			'foreignKey' => 'newsletter_id',
			'associationForeignKey' => 'user_id',
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

	var $belongsTo = array(
		'Campaign' => array(
			'className' => 'Newsletter.Campaign',
			'counterCache' => true,
			),
		'Newsletter.Template'
		);
}

?>