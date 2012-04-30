<?php
	$out = array();
	
	foreach($files as $file) {
		$check = file_exists($file) && !is_dir($file) && basename($file) != 'empty';
		if(!$check) {
			continue;
		}
		
		$size = @getimagesize($file);
		
		$file = str_replace(APP, '', $file);
		list($plugin, $path) = explode('/webroot/img/', $file);
		$plugin = basename($plugin);
		
		if(is_array($size)) {
			$sizeType = 'height';
			$sizeInt = $size[1];
			if($size[0] > $size[1]) {
				$sizeType = 'width';
				$sizeInt = $size[0];
			}
			
			$image = $this->Html->image(
				$plugin . '.' . $path,
				array(
					$sizeType => ($sizeInt > 60 ? 60 : $sizeInt) . 'px',
				)
			);
			
			$image = $this->Html->link(
				$image,
				$this->Html->assetUrl($plugin. '.' . $path, array('pathPrefix' => IMAGES_URL)) . '?width=640',
				array(
					'escape' => false,
					'class' => 'thickbox zoom',
					'target' => '_blank'
				)
			);
		}
		
		if(empty($image)) {
			$image = 'other';
		}
		
		$out[] = sprintf(
			'<div class="file ext_%s"><div class="image"><center>%s</center></div><span>%s</span></div>',
			preg_replace('/^.*\./', '', $file),
			$image,
			basename(htmlentities($file))
		);
	}
		
	echo implode('', $out);