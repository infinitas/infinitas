<?php
/**
 * Reset password form
 *
 * @package Infinitas.Users.View
 */

echo $this->Form->create();
	echo $this->Form->input('id');
	echo $this->Form->input('email', array('readonly' => 'readonly'));
	if (Configure::read('Website.login_type') == 'username') {
		echo $this->Form->input('username', array('readonly' => 'readonly'));
	}
	echo $this->Form->input('new_password', array('type' => 'password'));
	echo $this->Form->input('confirm_password', array('type' => 'password'));
echo $this->Form->end(__d('users', 'Reset Password'));