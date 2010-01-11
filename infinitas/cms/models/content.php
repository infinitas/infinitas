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

class Content extends CmsAppModel {
	var $name = 'Content';

	var $order = array(
		'Content.category_id' => 'ASC',
		'Content.ordering' => 'ASC'
		);

	var $validate = array(
		'title' => array(
			'notempty' => array('rule' => array('notempty')),
			),
		);

	var $actsAs = array(
		'Libs.Sluggable',
		'Libs.Viewable'
		);

	var $belongsTo = array(
		'Category' => array(
			'className' => 'Cms.Category',
			'counterCache' => true
			),
		'Core.Group',
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

	var $hasMany = array(
		'Cms.ContentFrontpage',
		'Cms.Feature',
		);
}

?>