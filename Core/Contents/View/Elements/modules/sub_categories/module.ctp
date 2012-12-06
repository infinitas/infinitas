<?php
$config = array_merge(array(
	'explore' => __d('contents', 'Explore this category'),
	'view_all' => __d('contents', 'View all categories'),
	'nothing_found' => __d('contents', 'No sub categories found'),
	'back' => __d('contents', 'Back to %s'),
	'div_class' => 'widget-content',
	'ul_class' => 'arrow-list'
), $config);

if (!empty($subCategory)) {
	$links = array();
	foreach ($subCategory as $category) {
		$url = $this->Event->trigger('Contents.slugUrl', array('type' => 'category', 'data' => array('GlobalCategory' => $category)));
		$links[] = $this->Html->link($category['title'], current($url['slugUrl']));
	}
}

if (empty($links)) {
	$links = array($config['nothing']);
}

if (!empty($parentCategory['id'])) {
	$url = $this->Event->trigger('Contents.slugUrl', array('type' => 'category', 'data' => array('GlobalCategory' => $parentCategory)));
	$links[] = $this->Html->link(
		sprintf($config['back'], $parentCategory['title']),
		current($url['slugUrl'])
	);
}

$exploreLink = !empty($this->request->params['category']) &&
	(($this->request->params['controller'] == 'global_categories' && $this->request->params['action'] != 'view') ||
	$this->request->params['controller'] != 'global_categories');

if ($exploreLink) {
	$url = $this->Event->trigger('Contents.slugUrl', array(
		'type' => 'category',
		'data' => array('GlobalCategory' => array('slug' => $this->request->params['slug']))
	));
	$links[] = $this->Html->link(
		__d('contents', $config['explore']),
		current($url['slugUrl'])
	);
}

$links[] = $this->Html->link($config['view_all'], array(
	'plugin' => 'contents',
	'controller' => 'global_categories',
	'action' => 'index'
));

echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('h3', $config['title']),
	$this->Design->arrayToList($links, array(
		'div' => $config['div_class'],
		'ul' => $config['ul_class']
	))
)), array('class' => 'widget'));