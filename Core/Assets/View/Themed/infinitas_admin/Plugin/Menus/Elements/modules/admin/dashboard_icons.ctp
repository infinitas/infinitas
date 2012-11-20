<?php
/**
 * Display a list of available plugin links
 *
 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Menu.Module
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.8a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
$icons = $this->Menu->builDashboardLinks();

if(!empty($icons['core'])) {
	echo $this->Html->tag('h3', __d('infinitas', 'Infinitas'));
	echo $this->Design->arrayToList($icons['core'], array(
		'ul' => 'thumbnails'
	));
}

if(!empty($icons['plugin'])) {
	echo $this->Html->tag('h3', __d('infinitas', 'Plugins'));
	echo $this->Design->arrayToList($icons['plugin'], array(
		'ul' => 'thumbnails'
	));
}