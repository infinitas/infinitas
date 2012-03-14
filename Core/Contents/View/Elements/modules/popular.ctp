<?php
	$defaultConfig = array(
		'title' => 'Latest Content',
		'limit' => 5,
		'title_length' => 60
	);
	
	echo $this->element('Contents.modules/latest', array('config' => $config, 'findMethod' => 'popularList'));