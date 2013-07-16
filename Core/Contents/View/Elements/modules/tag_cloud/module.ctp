<?php
$config = array_merge(array(
	'title' => __d('contents', 'Tag cloud'),
	'limit' => 50,
	'model' => null,
	'category' => null,
	'tag_before' => '<li size="%size%" class="tag">',
	'tag_after' => '</li>',
	'box' => true
), $config);
if (!empty($config['id']) && strstr($config['id'], '.') !== false) {
	$config['model'] = $config['id'];
}
if (!empty($config['model'])) {
	list($plugin,) = pluginSplit($config['model']);
	if (Inflector::underscore($plugin) != $this->request->plugin && empty($config['id'])) {
		return;
	}
}

if (isset($config['category']) && $config['category'] && !empty($this->request->params['category'])) {
	$config['category'] = $this->request->params['category'];
}

if (empty($tags) && !empty($config['tags'])) {
	$tags = $config['tags'];
}
if (empty($tags)) {
	if (!empty($config['id']) && (!is_int($config['id']) || strlen($config['id']) !== 36)) {
		unset($config['id']);
	}
	$tags = ClassRegistry::init('Blog.BlogPost')->GlobalTagged->find('cloud', array(
		'limit' => $config['limit'],
		'model' => $config['model'],
		'category' => $config['category'],
		'foreign_key' => !empty($config['id']) ? $config['id'] : null,
	));
}

// format is different of views / the find above
if (!isset($tags[0]['GlobalTag'])) {
	$_tags = array();
	foreach ($tags as $tag) {
		$_tags[]['GlobalTag'] = $tag;
	}
	$tags = $_tags;
	unset($_tags);
}
if (!$tags) {
	return false;
}

$url = !empty($config['url']) ? $config['url'] : array();
if (empty($url) && !empty($config['model'])) {
	list($plugin, $model) = pluginSplit($config['model']);
	$url['plugin'] = Inflector::underscore($plugin);
	$url['controller'] = Inflector::tableize($model);
	$url['action'] = 'index';
}

if (!empty($config['category'])) {
	$url['category'] = $config['category'];
}

$tags = $this->TagCloud->display($tags, array(
	'before' => $config['tag_before'],
	'after'  => $config['tag_after'],
	'url' => $url,
	'named' => 'tag'
));
if (!$config['box']) {
	echo $tags;
	return;
}

echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('h4', $config['title']),
	$this->Html->tag('div', $tags, array('class' => 'content'))
)),array('class' => 'span3'));