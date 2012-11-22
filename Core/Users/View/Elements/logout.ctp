<?php
/**
 * Logout element
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Users.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

echo $this->Html->tag('div', implode('', array(
	__d('users', 'Welcome, %s', $this->Html->tag('em', AuthComponent::user('prefered_name'))),
	$this->Html->link($this->Html->tag('i', '', array('class' => 'icon-remove-circle')), array(
		'plugin' => 'users',
		'controller' => 'users',
		'action' => 'logout'
	), array('escape' => false))
)), array('class' => 'pull-right welcome'));