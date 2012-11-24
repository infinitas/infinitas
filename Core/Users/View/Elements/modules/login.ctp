<?php
/*
 * Login module
 *
 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
 * @link http://www.infinitas-cms.org
 * @package Infinitas.Users.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

	if (AuthComponent::user('id')) {
		echo $this->element('Users.logout');
	} else {
		echo $this->Form->create('User', array(
			'url' => array(
				'plugin' => 'users',
				'controller' => 'users',
				'action' => 'login'
			),
			'inputDefaults' => array(
				'div' => false,
				'label' => false
			),
			'class' => 'navbar-form pull-right'
		));
			echo $this->Form->input('username', array(
				'placeholder' => __d('users', 'Username'),
				'class' => 'span2'
			));
			echo $this->Form->input('password', array(
				'placeholder' => __d('users', 'Password'),
				'class' => 'span2'
			));
			echo $this->Form->button(__d('users', 'Login'), array(
				'class' => 'btn'
			));
		echo $this->Form->end();
	}