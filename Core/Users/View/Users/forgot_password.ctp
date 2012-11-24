<?php
	echo $form->create('User');
		echo $form->input('email');
	echo $form->end(__d('users', 'Reset Password'));
?>