<?php
	/**
	 * Security Configuration
	 */
	$config['Security'] = array(
		'level' => 'medium',
		'salt' => 'dev-DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi',
		'Cookie' => array(
			'name' => 'InfinitasCookie',
			'key' => 'is~#^Ow!aAqs*&sXHKb$@1v!@*(XSLdre@34S#$%)asGqSI2321~_+!@#',
			'domain' => '',
			'time' => '5 days',
			'path' => '/',
			'secure' => false,
			'http_only' => false
		),
		'login_attempts' => 3,

		'encryption_secret' => '­sç„¢˜4m™¤ÀšžË',
		'encryption_salt' => '™¤Àšž'
	);