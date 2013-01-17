<?php
/**
* Campaign
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @filesource
* @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
* @link          http://infinitas-cms.org
* @package       sort
* @subpackage    sort.comments
* @license       http://www.opensource.org/licenses/mit-license.php The MIT License
* @since         0.5a
*/

echo $this->Form->create();
	echo $this->Infinitas->adminEditHead();
	echo $this->Form->input('id');

	$tabs = array(
		__d('newsletter', 'Details')
	);
	$contents = array(
		$this->Infinitas->wysiwyg('NewsletterCampaign.description')
	);

	echo $this->Html->tag('div', implode('', array(
		$this->Html->tag('div', $this->Design->tabs($tabs, $contents), array(
			'class' => 'span9'
		)),
		$this->Html->tag('div', implode('', array(
			$this->Form->input('name'),
			$this->Form->input('slug', array(
				'label' => __d('newsletter', 'Alias')
			)),
			$this->Form->input('newsletter_template_id', array(
				'empty' => Configure::read('Website.empty_select'),
				'label' => __d('newsletter', 'Template')
			)),
			$this->Form->input('active')
		)), array('class' => 'span3'))
	)), array('class' => 'row'));
echo $this->Form->end();