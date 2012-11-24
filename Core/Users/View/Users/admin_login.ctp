<?php
$this->Session->flash('auth');
echo $this->Form->create(null, array(
	'inputDefaults' => array(
		'label' => false
	)
));
	if (Configure::read('Website.login_type') == 'email') {
		echo $this->Form->input('email', array(
			'placeholder' => __d('users', 'Email')
		));
	} else {
		echo $this->Form->input('username', array(
			'placeholder' => __d('users', 'Username'),
		));
	}
	echo $this->Form->input('password', array(
		'placeholder' => __d('users', 'Password')
	));
	echo $this->Form->input('language', array(
		'options' => array(
			'en' => 'English'
		),
		'empty' => __d('infinitas', 'Default language')
	));
	echo $this->Form->input('remember_me', array(
		'label' => __d('infinitas', 'Remember me'),
		'type' => 'checkbox',
		'div' => true
	));
	echo $this->Form->button(__d('infinitas', 'Login'), array(
		'class' => array(
			'btn',
			'btn-primary'
		)
	));
echo $this->Form->end();