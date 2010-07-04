<?php
	$this->Session->flash('auth');
	echo $this->Form->create('User', array('action' => 'login'));
		if(Configure::read('Website.login_type') == 'email'){
			echo $this->Form->input('email');
		}
		else{
			echo $this->Form->input('username');
		}
		echo $this->Form->input('password');
		echo $this->Form->input('language', array('type' => 'select'));
	echo $this->Form->end('Login');

	echo $this->Html->link(
		__('Forgot Password', true),
		array(
			'plugin' => 'management',
			'controller' => 'users',
			'action' => 'forgot_password'
		)
	);

	echo $this->Html->link(
		__('Register', true),
		array(
			'plugin' => 'management',
			'controller' => 'users',
			'action' => 'register'
		)
	);
?>
