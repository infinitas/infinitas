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
* @link http://www.dogmatic.co.za
* @package sort
* @subpackage sort.comments
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.5a
*/

class Template extends NewsletterAppModel {
	var $name = 'Template';

	var $order = array('Template.name' => 'ASC');

	var $validation = array(
		'name' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'A template with that name already exists.'
				)
			)
		);

	var $hasMany = array(
		'Newsletter.Newsletter',
		'Newsletter.Campaign'
		);

	var $belongsTo = array(
		'Locker' => array(
			'className' => 'Core.User',
			'foreignKey' => 'locked_by',
			'conditions' => '',
			'fields' => array(
				'Locker.id',
				'Locker.username'
				),
			'order' => ''
			)
		);
}

?>