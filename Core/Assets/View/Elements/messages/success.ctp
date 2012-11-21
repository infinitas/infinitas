<?php
	if($code) {
		$message = $this->Html->tag('strong', $code . ':') . $message;
	}
	echo $this->Html->tag('div', implode('', array(
		$this->Form->button('&times;', array('class' => 'close', 'data-dismiss' => 'alert')),
		$message
	)), array('class' => array('alert', 'alert-success')));