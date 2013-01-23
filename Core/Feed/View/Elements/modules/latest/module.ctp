<?php
$config = array_merge(array(
	'title' => __d('feed', 'News feed'),
	'feed' => '',
	'event_before' => true,
	'event_after' => true
), $config);

if (empty($config['feed']) && !empty($config['id'])) {
	$config['feed'] = $config['id'];
}

if (empty($feeds)) {
	$config['feed'] = isset($config['feed']) ? $config['feed'] : '';
	$feeds = ClassRegistry::init('Feed.Feed')->getFeed($config['feed'], AuthComponent::user('group_id'));
}

if (empty($feeds)) {
	return;
}

$contents = array();
$eventHeader = $this->Html->tag('div', ':content', array('class' => ':class'));
foreach ($feeds as &$feed) {

	/*if (false && $config['event_before']) {
		$eventData = $this->Event->trigger('feedBeforeContentRender', array('_this' => $this, 'feed' => $feed['Feed']));
		$eventData = (array)$eventData['feedBeforeContentRender'];
		foreach ($eventData as $_plugin => &$_data) {
			$_data = String::insert($eventHeader, array(
				'content' => $_data,
				'class' => $_plugin
			));
		}
		$contents[] = $this->Html->tag('div', implode('', $eventData), array(
			'class' => 'beforeEvent'
		));
	}*/
	$eventData = $this->Event->trigger(Inflector::camelize($feed['Feed']['plugin']) . '.slugUrl', array(
		'type' => $feed['Feed']['controller'],
		'data' => $feed['Feed']
	));
	$urlArray = current($eventData['slugUrl']);
	$email = null;
	if (Validation::email($feed['Feed']['title'])) {
		$email = $this->Gravatar->image($feed['Feed']['title']);
		$feed['Feed']['title'] = __d('feed', 'Comment');
	}

	$feed = $this->Html->tag('div',
		$this->Html->tag('div', implode('', array(
			$this->Html->tag('h2', $this->Html->tag('span', $feed['Feed']['title'])),
			$this->Html->tag('div', CakeTime::format(Configure::read('Feed.time_format'), $feed['Feed']['date']), array(
				'class' => 'date_flag'
			)),
			$this->Html->tag('div', $email . $this->Text->truncate(strip_tags($feed['Feed']['body']), 350), array(
				'class' => array('content')
			)),
			$this->Html->tag('div', $this->Html->link(Configure::read('Website.read_more'), $urlArray, array(
				'class' => 'button_small'
			)), array('class' => 'blog_footer'))
		)), array('class' => array('introduction'))),
	array('class' => 'wrapper'));

	/**if (false && $config['event_after']) {
		$eventData = $this->Event->trigger('feedAfterContentRender', array('_this' => $this, 'feed' => $feed));
		$eventData = (array)$eventData['feedAfterContentRender'];
		foreach ($eventData as $_plugin => &$_data) {
			$_data = String::insert($eventHeader, array(
				'content' => $_data,
				'class' => $_plugin
			));
		}
		$contents[] = $this->Html->tag('div', implode('', $eventData), array(
			'class' => 'afterEvent'
		));
	}*/
}

echo $this->Html->tag('h2', $this->Html->tag('span', $config['title']));
echo $this->Html->tag('div', implode('', $feeds), array(
	'class' => 'feed'
));