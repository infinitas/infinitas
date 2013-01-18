<?php
    /**
     * newsletter creation
	 *
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
     * @link http://infinitas-cms.org
     * @package Infinitas.Newsletter.View
     * @license http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since 0.5a
	 *
	 * @author Carl Sutton <dogmatic69@gmail.com>
     */

echo $this->Form->create('Newsletter');
	echo $this->Infinitas->adminEditHead();
	echo $this->Form->input('id');

	$tabs = array(
		__d('newsletter', 'HTML version'),
		__d('newsletter', 'Text version'),
	);
	$contents = array(
		$this->Infinitas->wysiwyg('Newsletter.html'),
		$this->Form->input('text', array(
			'class' => 'span12'
		))
	);

	echo $this->Html->tag('div', implode('', array(
		$this->Html->tag('div', $this->Design->tabs($tabs, $contents), array(
			'class' => 'span9'
		)),
		$this->Html->tag('div', implode('', array(
			$this->Form->input('slug'),
			$this->Form->input('campaign_id', array(
				'empty' => Configure::read('Website.empty_select')
			)),
			$this->Form->input('from'),
			$this->Form->input('reply_to'),
			$this->Form->input('subject')
		)), array('class' => 'span3'))
	)), array('class' => 'row'));
echo $this->Form->end();