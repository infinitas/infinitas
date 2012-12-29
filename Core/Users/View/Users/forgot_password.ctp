<?php
	echo $this->Form->create('User');
		echo $this->Form->input('email');
	echo $this->Form->end(__d('users', 'Reset Password'));