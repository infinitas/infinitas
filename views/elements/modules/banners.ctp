<?php
	App::import( 'Folder' );
	$folder = new Folder();

	$this->path = isset($config['path']) ? (string)$config['path'] : '';
	if(!isset($config['path']) || empty($config['path'])){
		return false;
	}
	$imagePath = APP.'views/themed/'.Configure::read('Theme.name').'/webroot/img'.$config['path'];


	$folder->cd($imagePath);
	$_images = $folder->find('(.*?)\.(jpg|jpeg|png|gif)$');

	foreach((array)$_images as $image){
		echo $this->Html->image('/theme/'.Configure::read('Theme.name').'/img'.$config['path'].'/'.$image, array('width' => isset($config['width']) ? $config['width']: '500px'));
	}
?>