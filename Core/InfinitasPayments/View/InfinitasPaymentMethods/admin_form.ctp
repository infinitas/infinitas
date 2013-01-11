<?php
/**
 * payment method add / edit form
 *
 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
 *
 * @link http://infinitas-cms.org/InfinitasPayments
 * @package	InfinitasPayments.views.admin_edit
 * @license	http://infinitas-cms.org/mit-license The MIT License
 * @since 0.9b1
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */
echo $this->Form->create();
	echo $this->Infinitas->adminEditHead();
	echo $this->Form->input('id');
	$tabs = array(
		__d('infinitas_payments', 'Live'),
		__d('infinitas_payments', 'Sandbox'),
	);
	$contents = array(
		$this->Form->input('live', array(
			'label' => false,
			'class' => 'span12'
		)),
		$this->Form->input('sandbox', array(
			'label' => false,
			'class' => 'span12'
		))
	);

	echo $this->Html->tag('div', implode('', array(
		$this->Html->tag('div', $this->Design->tabs($tabs, $contents), array(
			'class' => 'span8'
		)),
		$this->Html->tag('div', implode('', array(
			$this->Form->input('name'),
			$this->Form->input('slug'),
			$this->Form->input('testing', array(
				'label' => __d('infinitas_payments', 'Force sandbox mode'),
			)),
			$this->Form->input('active', array(
				'label' => __d('infinitas_payments', 'Make payment method available')
			)),
		)), array('class' => 'span4'))
	)), array('class' => 'row'));
echo $this->Form->end();
