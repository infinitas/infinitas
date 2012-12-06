<?php
	$config = array_merge(array(
		'feed' => '',
		'event_before' => true,
		'event_after' => true
	), $config);
?>
<div class="feed">
	<?php
		if (empty($feeds)) {
			$config['feed'] = isset($config['feed']) ? $config['feed'] : '';
			$feeds = ClassRegistry::init('Feed.Feed')->getFeed($config['feed'], AuthComponent::user('group_id'));
		}

		$eventHoder = $this->Html->tag('div', ':content', array('class' => ':class'));
		foreach ($feeds as $feed) {
			$model = Inflector::camelize(Inflector::singularize($feed['Feed']['controller']));
			$feed[$model] =& $feed['Feed'];

			if ($config['event_before']) {
				$eventData = $this->Event->trigger('feedBeforeContentRender', array('_this' => $this, 'feed' => $feed));
				$eventData = (array)$eventData['feedBeforeContentRender'];
				foreach ($eventData as $_plugin => &$_data) {
					$_data = String::insert($eventHoder, array(
						'content' => $_data,
						'class' => $_plugin
					));
				}
				echo $this->Html->tag('div', implode('', $eventData), array(
					'class' => 'beforeEvent'
				));
			}

			$eventData = $this->Event->trigger($feed['Feed']['plugin'] . '.slugUrl', array(
				'type' => $feed['Feed']['controller'],
				'data' => $feed
			));
			$urlArray = current($eventData['slugUrl']);
			echo $this->Html->tag('div',
				$this->Html->tag('div', implode('', array(
					$this->Html->tag('h2', implode('', array(
						$this->Html->link($feed['Feed']['title'], $urlArray),
						$this->Html->tag('small', $this->Time->niceShort($feed['Feed']['date']))
					))),
					$this->Html->tag('div', $this->Text->truncate($feed['Feed']['body'], 350, array('html' => true)), array(
						'class' => array('content', $this->layout)
					))
				)), array('class' => array('introduction', $this->layout))),
			array('class' => 'wrapper'));

			if ($config['event_after']) {
				$eventData = $this->Event->trigger('feedAfterContentRender', array('_this' => $this, 'feed' => $feed));
				$eventData = (array)$eventData['feedAfterContentRender'];
				foreach ($eventData as $_plugin => &$_data) {
					$_data = String::insert($eventHoder, array(
						'content' => $_data,
						'class' => $_plugin
					));
				}
				echo $this->Html->tag('div', implode('', $eventData), array(
					'class' => 'afterEvent'
				));
			}
		}
	?>
</div>