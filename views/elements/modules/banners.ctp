<?php
	App::import( 'Folder' );
	$folder = new Folder();

	$this->path = isset($config['path']) ? (string)$config['path'] : '';
	if(!isset($config['path']) || empty($config['path'])){
		return false;
	}

	$folder->cd($config['path']);
	$_images = $folder->find('(.*?)\.(jpg|jpeg|png|gif)$');

	$config['path'] = str_replace('\\\\', '\\', $config['path']);
	$config['path'] = str_replace('\\\\', '\\', $config['path']);
	$config['path'] = str_replace('\\\\', '\\', $config['path']);
	$config['path'] = str_replace(APP.'views\\themed\\'.Configure::read('Theme.name').'\\webroot\\img', '', $config['path']);

	foreach((array)$_images as $image){
		echo $this->Html->image('/theme/'.Configure::read('Theme.name').'/img'.str_replace('\\', '/', $config['path']).'/'.$image, array('width' => isset($config['width']) ? $config['width']: '500px'));
	}
?>