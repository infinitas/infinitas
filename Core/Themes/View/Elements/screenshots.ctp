<?php
$images = InfinitasTheme::screenshots($theme);

if(empty($images)) {
	return $this->Design->alert(__d('themes', 'There are no screen shots available for this theme'));
}

$main = array();
foreach($images as $k => &$image) {
	$layout = str_replace('.png', '', basename($image));
	$image = $this->Html->link(
		$this->Html->image($image),
		$image,
		array(
			'class' => array(
				'thumbnail',
				'thickbox'
			),
			'escape' => false
		)
	);
	$image .= $this->Html->tag('h4', Inflector::humanize($layout));

	if(in_array($layout, array('admin', 'front'))) {
		$main[] = $image;
		unset($images[$k]);
	}
}

if(!empty($main)) {
	echo $this->Html->tag('h3', __d('themes', 'Main layouts'));
	echo $this->Design->arrayToList($main, array(
		'ul' => 'thumbnails',
		'li' => 'span4'
	));
}

if(!empty($images)) {
	echo $this->Html->tag('h3', __d('themes', 'Additional layouts'));
	echo $this->Design->arrayToList($images, array(
		'ul' => 'thumbnails',
		'li' => 'span3'
	));
}