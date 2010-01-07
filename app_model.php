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

class AppModel extends Model {
	/**
	* The database configuration to use for the site.
	*/
	var $useDbConfig = 'default';

	/**
	* Behaviors to attach to the site.
	*/
	var $actsAs = array('Containable', 'Core.Lockable');
}

?>