<?php
	$this->Session->flash('auth');
	echo $this->Form->create('User');
		echo $this->Form->input('username');
		echo $this->Form->input('password');
	echo $this->Form->end('Login');
?>