<?php
/**
 * Login form
 *
 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org
 * @package Users.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since v0.9a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
echo $this->Html->tag('h4', __d('users', 'Members Login'));
echo $this->Form->create('User', array(
	'url' => array(
		'plugin' => 'users',
		'controller' => 'users',
		'action' => 'login'
	),
	'inputDefaults' => array(
		'label' => false
	)
));
	echo $this->Form->input('username', array(
		'placeholder' => __d('users', 'Username')
	));
	echo $this->Form->input('password', array(
		'placeholder' => __d('users', 'Password')
	));
	echo $this->Form->input('remember_me', array(
		'label' => __d('infinitas', 'Remember me'),
		'type' => 'checkbox'
	));
	echo $this->Form->submit('Login', array('class' => 'niceLink'));
echo $this->Form->end();
