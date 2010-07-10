<?php
    echo $form->create('User');
        echo $form->input('id');
        echo $form->input('email', array('readonly' => 'readonly'));
		if(Configure::read('Website.login_type') == 'username'){
        	echo $form->input('username', array('readonly' => 'readonly'));
		}
        echo $form->input('new_password', array('type' => 'password'));
        echo $form->input('confirm_password', array('type' => 'password'));
    echo $form->end(__('Reset Password', true));
?>