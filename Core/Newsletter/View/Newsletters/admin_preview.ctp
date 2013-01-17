<?php
/**
 * Newsletter preview
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 *
 * @link http://infinitas-cms.org
 * @package Infinitas.Newsletter.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 */

echo $this->Html->tag('base', '', array(
	'href' => Configure::read('Site.base_url')
));
echo implode('', array(
	$newsletter['Template']['header'],
	$newsletter['Newsletter']['html'],
	$newsletter['Template']['footer']
));