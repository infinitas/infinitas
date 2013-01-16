<?php
if (empty($this->request->pass[0])) {
	$this->request->pass = array('');
}

$hilightOptions = array(
	'regex' => "|\b%s\b|iu",
	'format' => '<b class="search">\1</b>'
);

$results = array();
$template = $this->Html->tag('div', implode('', array(
	$this->Html->tag('span', '%s', array('class' => 'link')),
	$this->Html->tag('span', '%s', array('class' => 'url')),
	$this->Html->tag('p', '%s')
)), array('class' => 'result'));
foreach ($search as &$s) {
	$s['GlobalContent']['title'] = String::highlight($s['GlobalContent']['title'], $this->request->pass[0], $hilightOptions);
	$s['GlobalContent']['body'] = String::highlight(String::excerpt(strip_tags($s['GlobalContent']['body']), $this->request->pass[0]), $this->request->pass[0], $hilightOptions);
	$s['GlobalContent']['url'] = InfinitasRouter::url($this->GlobalContents->url($s));

	$results[] = sprintf(
		$template,
		$this->Html->link(String::truncate($s['GlobalContent']['title'], 60, array('html' => true)), $s['GlobalContent']['url'], array('escape' => false)),
		$s['GlobalContent']['url'],
		$s['GlobalContent']['body']
	);
}

echo $this->Html->tag('div', implode('', array(
	$this->Form->create(null, array('inputDefaults' => array(
		'label' => false,
		'div' => false
	))),
	$this->Form->input('search', array('value' => $this->request->pass[0])),
	$this->Form->input('global_category_id', array('options' => $globalCategories)),
	$this->Form->submit(__d('contents', 'Search'), array('class' => 'submit')),
	$this->Form->end()
)), array('class' => 'search'));

echo implode('', $results);
echo $this->element('pagination/navigation');