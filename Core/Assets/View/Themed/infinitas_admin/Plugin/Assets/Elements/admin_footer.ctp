<?php
$copy = __d('infinitas', '&copy; %s %d v%s',
	$this->Html->link('Infinitas', 'http://infinitas-cms.org'),
	date('Y'),
	$this->Html->tag('em', Configure::read('Infinitas.version'))
);
echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('div',
		$this->Html->tag('div', $copy, array(
			'class' => 'credit'
		)),
	array('class' => 'navbar-inner')),
)), array('class' => 'navbar navbar-fixed-bottom', 'id' => 'footer'));