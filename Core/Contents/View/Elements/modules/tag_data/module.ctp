<?php
$config = array_merge(array(
	'missing' => 'There does not seem to be a description for this tag',
	'title' => 'Showing posts related to "%s"'
), $config);

if (empty($tagData['GlobalTag']['description'])) {
	return;
}

if (!$tagData['Tag']['description']) {
	$tagData['Tag']['description'] = $this->Html->tag('p', __d($this->request->params['plugin'], $config['missing']));
}
echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('h2', __d($this->request->params['plugin'], $config['title'], $tagData['GlobalTag']['name'])),
	$this->Html->tag('blockquote',
		$this->Html->tag('div', $tagData['Tag']['description'], array(
			'class' => 'description'
		))
	)
)), array('class' => 'tag-details'));