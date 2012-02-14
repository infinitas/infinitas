<?php
	$this->Session->flash('auth');
	echo $this->Form->create('User');
		if(Configure::read('Website.login_type') == 'email'){
			echo $this->Form->input('email');
		}
		else{
			echo $this->Form->input('username');
		}
		echo $this->Form->input('password');
	echo $this->Form->end('Login');
?>