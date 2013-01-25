<?php
if (empty($comments)) {
	echo $this->Design->alert('You have not made any comments');
	return;
}

foreach ($comments as &$comment) {
	$primaryKey = ClassRegistry::init($comment['InfinitasComment']['class'])->primaryKey;
	list($plugin, $model) = pluginSplit($comment['InfinitasComment']['class']);
	$url = current($this->Event->trigger($plugin . '.slugUrl', array(
		'data' => array(
			$model => array(
				$primaryKey => $comment['InfinitasComment']['foreign_id']
			)
		)
	)));
	$url[$plugin][] = $url[$plugin][$primaryKey];
	unset($url[$plugin][$primaryKey]);
	$url[$plugin]['#'] = $comment['InfinitasComment']['id'];
	$comment = $this->Html->tag('div', implode('', array(
		$this->Html->tag('h3', CakeTime::format(Configure::read('Blog.time_format'), $comment['InfinitasComment']['created'])),
		String::truncate($comment['InfinitasComment']['comment']),
		$this->Html->link(Configure::read('Website.read_more'), $url[$plugin], array(
			'class' => 'perma-link'
		))
	)));
}

echo implode('', $comments);