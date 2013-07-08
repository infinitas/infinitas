<?php
echo $this->Html->tag('div', implode('', array(
	$this->Html->tag('div', implode('', array(
		$this->Html->tag('h4', $data['title']),
		$this->Html->image($data['content_image_path_large'], array(
			'class' => 'large-icon ie6fix'
		)),
	)), array('class' => 'highlight-box')),
	$this->Html->tag('p', String::truncate($data['introduction'], 200, array('html' => true))),
	$this->Html->link(Configure::read('Website.read_more'), $data['link'], array(
		'class' => 'arrow-link',
		'title' => $data['title'],
	)),
)), array('class' => 'span2'));