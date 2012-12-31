<?php
/**
 * Registration form
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

echo $this->Html->tag('h4', __d('users', 'New user'));
echo $this->Form->create('User', array(
	'inputDefaults' => array(
		'label' => false
	),
	'autocomplete' => 'off'
));
	echo $this->Form->input('full_name', array(
		'placeholder' => __d('users', 'Full name')
	));
	if (Configure::read('Website.login_type') == 'username') {
		echo $this->Form->input('username', array(
		'placeholder' => __d('users', 'Username')
	));
	}
	echo $this->Form->input('email', array(
		'placeholder' => __d('users', 'Email')
	));
	echo $this->Form->input('password', array(
		'placeholder' => __d('users', 'Password'),
		'class' => 'password'
	));
	echo $this->Form->input('terms', array(
		'label' => __d('infinitas', 'I accept the terms and conditions'),
		'type' => 'checkbox'
	));
echo $this->Form->end(__d('users', 'Register'));