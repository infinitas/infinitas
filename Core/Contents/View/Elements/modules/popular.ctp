<?php
	$config = array_merge(array(
		'title' => __d('contents', 'Popular Content'),
		'limit' => 5,
		'title_length' => 60
	), $config);

	echo $this->element('Contents.modules/latest', array('config' => $config, 'findMethod' => 'popularList'));