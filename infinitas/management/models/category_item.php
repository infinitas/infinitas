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

class CategoryItem extends ManagementAppModel {
	var $name = 'CategoryItem';
	var $tablePrefix = 'global_';

	var $belongsTo = array(
		'Category' => array(
			'className' => 'Management.Category',
			'counterCache' => 'item_count'
		)
	);
}

?>