<?php
/**
 * Menus form
 *
 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link http://infinitas-cms.org
 * @package Infinitas.Menus.View
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @since 0.5a
 *
 * @author Carl Sutton <dogmatic69@gmail.com>
 */

echo $this->Form->create('MenuItem');
	echo $this->Infinitas->adminEditHead();
	echo $this->Form->input('id');

	$tabs = array(
		__d('menus', 'Url'),
		__d('menus', 'Config'),
		__d('menus', 'Advanced')
	);
	$prefixes = Configure::read('Routing.prefixes');
	$prefixes = array_merge(array(
		null => __d('menus', 'Default prefix')
	), array_combine($prefixes, $prefixes));
	$contents = array(
		implode('', array(
			$this->Html->tag('div', implode('', array(
				$this->Form->input('link', array(
					'label' => __d('menus', 'Url')
				)),
			)), array('class' => 'external')),
			$this->Html->tag('div', implode('', array(
				$this->element('Routes.route_select'),
				$this->Form->input('prefix', array(
					'options' => $prefixes
				)),
				$this->Form->input('params', array('type' => 'textarea')),
				$this->Form->label(__d('menus', 'Force this link to open:')),
				$this->Form->input('force_backend', array(
					'label' => __d('menus', 'in the backend')
				)),
				$this->Form->input('force_frontend', array(
					'label' => __d('menus', 'in the frontend')
				)),
			)), array('class' => 'internal')),
		)),
		implode('', array(
			$this->Form->input('menu_id', array(
				'type' => 'select',
				'class' => "ajaxSelectPopulate {url:{action:'getParents'}, target:'MenuItemParentId'}",
				'empty' => Configure::read('Website.empty_select')
			)),
			$this->Form->input('parent_id', array(
				'type' => 'select',
			)),
			$this->Form->input('group_id', array('empty' => Configure::read('Website.empty_select'))),
		)),
		implode('', array(
			$this->Form->input('title'),
			$this->Form->input('image'),
			$this->Form->input('id'),
			$this->Form->input('class'),
			$this->Form->input('target', array(
				'options' => MenuItem::targets()
			)),
		))
	);

	echo $this->Html->tag('row', implode('', array(
		$this->Html->tag('div', implode('', array(
			$this->Html->tag('h4', __d('menus', 'Menu Item')),
			$this->Form->input('name', array(
				'label' => __d('menu', 'Link text')
			)),
			$this->Form->input('active', array(
				'label' => __d('menu', 'Menu item is visible')
			)),
			$this->Form->label(__d('menus', 'Url type')),
			$this->Form->input('_type', array(
				'legend' => false,
				'options' => array(
					'external' => __d('menus', 'External URL'),
					'internal' => __d('menus', 'Internal URL'),
				),
				'default' => 'internal',
				'value' => !empty($this->request->data['MenuItem']['link']) ? 'external' : null,
				'type' => 'radio'
			))
		)), array('class' => 'span4')),
		$this->Html->tag('div', $this->Design->tabs($tabs, $contents), array('class' => 'span8')),
	)));
echo $this->Form->end(); ?>