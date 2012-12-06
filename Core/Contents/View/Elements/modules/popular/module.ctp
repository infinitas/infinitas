<?php
	$config = array_merge(array(
		'title' => __d('contents', 'Popular Content'),
	), $config);

	echo $this->element('Contents.modules/latest/module', array(
		'config' => $config,
		'findMethod' => 'popularList'
	));