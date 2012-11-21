<?php
$copy = __d('infinitas', '&copy; %s %d v%s',
	$this->Html->link('Infinitas', 'http://infinitas-cms.org'),
	date('Y'),
	$this->Html->tag('em', Configure::read('Infinitas.version'))
);
echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('div',implode('', array(
		$this->Html->tag('div', implode('', array(
			$this->Html->link($this->Html->tag('i', '', array('class' => 'icon-share-alt')) . __d('infinitas', 'View Site'), '/', array(
				'target' => '_blank',
				'escape' => false
			))
		)), array('class' => 'links')),
		$this->Html->tag('div', $copy, array(
			'class' => 'credit'
		))
	)),
	array('class' => 'navbar-inner muted')),
)), array('class' => 'navbar navbar-fixed-bottom', 'id' => 'footer'));