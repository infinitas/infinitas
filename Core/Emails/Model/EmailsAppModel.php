<?php
/**
 * EmailsAppModel
 *
 * @package Infinitas.Emails.Model
 */

/**
 * EmailsAppModel
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Emails.Model
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.7a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

class EmailsAppModel extends AppModel {
/**
 * Custom table prefix
 *
 * @var string
 */
	public $tablePrefix = 'emails_';

/**
 * Email protocols
 *
 * @var array
 */
	public $types = array(
		'smtp' => 'smtp',
		'php' => 'php',
		'pop3' => 'pop3',
		'imap' => 'imap'
	);

}