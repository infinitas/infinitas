<?php
/**
 * Forgot password form
 *
 * @package Infinitas.Users.View
 */

echo $this->Form->create();
	echo $this->Form->input('email');
echo $this->Form->end(__d('users', 'Reset Password'));