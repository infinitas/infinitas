<?php
/**
 * View for managing theme details
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org
 * @package Infinitas.Themes.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.6a
 *
 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
 */

echo $this->Form->create(null, array(
	'inputDefaults' => array(
		'empty' => Configure::read('Website.empty_select')
	)
));
	echo $this->Infinitas->adminOtherHead(array('cancel'));
	echo $this->Form->input('id');

	$tabs = array(
		__d('themes', 'Theme'),
		__d('themes', 'Information')
	);

	$contents = array(
		implode('', array(
			$this->Form->input('name', array(
				'options' => $themes,
				'type' => 'select',
				'disabled' => true,
				'label' => __d('themes', 'Theme')
			)),
			!empty($defaultLayouts) ? $this->Form->input('default_layout', array('options' => $defaultLayouts)) : '',
			$this->Form->input('active')
		)),
		$this->request->data['Theme']['description']
	);

	$right = array(
		$this->Form->input('author', array('readonly' => true)),
		$this->Form->input('url', array('readonly' => true)),
		$this->Form->input('update_url', array('readonly' => true)),
		$this->Form->input('licence', array('readonly' => true))
	);
	echo $this->Html->tag('div', implode('', array(
		$this->Html->tag('div', $this->Design->tabs($tabs, $contents), array('class' => 'span9')),
		$this->Design->sidebar($right, 'Author Details')
	)), array('class' => 'row-fluid form'));
echo $this->Form->end();