<?php
/**
 * Log
 *
 * @package Infinitas.Management.Model
 */

/**
 * Log
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Management.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class Log extends ManagementAppModel {
/**
 * Custom table
 *
 * @var string
 */
	public $useTable = 'core_logs';

/**
 * Model data ordering
 *
 * @var array
 */
	public $order = array(
		'created' => 'DESC'
	);
	
}