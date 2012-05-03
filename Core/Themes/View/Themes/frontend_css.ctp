<?php
	foreach($css as $file) {
		list($plugin, $file) = pluginSplit($file);
		$path = InfinitasPlugin::path($plugin) . 'webroot' . DS . 'css' . DS . $file . '.css';
		
		if(is_file($path)) {
			echo file_get_contents($path);
		}
	}