<?php
	/**
	 * Management Modules admin edit post.
	 *
	 * this page is for admin to manage the modules on the site
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   management
	 * @subpackage	management.views.configs.admin_edit
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 */

	echo $this->Form->create(null, array(
		'inputDefaults' => array(
			'empty' => Configure::read('Website.empty_select')
		)
	));
		echo $this->Infinitas->adminEditHead();
		echo $this->Form->input('id');

		$parts = array(
			'heading' => __d('modules', 'Menu Item'),
			'toggle' => __d('modules', 'Toggle'),
			'module' => __d('modules', 'Module File'),
			'where' => __d('modules', 'Where Module should show'),
			'licence' => __d('module', 'Licence Details')
		);

		$tabs = array(
			__d('modules', 'Config'),
			__d('modules', 'Display'),
			__d('modules', 'Location')
		);
		$moduleConfig = $this->ModuleLoader->moduleConfig(array(
			'plugin' => !empty($this->data['Module']['plugin']) ? $this->data['Module']['plugin'] : null,
			'module' => !empty($this->data['Module']['module']) ? $this->data['Module']['module'] : null,
			'admin' => isset($this->data['Module']['admin']) ? $this->data['Module']['admin'] : false,
			'config' => !empty($this->data['Module']['config']) ? $this->data['Module']['config'] : null
		));

		$contents = array();
		$contents[] = $this->Html->tag('div', $moduleConfig, array(
			'class' => 'module-config'
		));

		$contents[] = implode('', array(
			$this->Form->input('group_id'),
			$this->Form->input('theme_id')
		));
		$contents[] = implode('', array(
			$this->Form->input('position_id'),
			$this->Form->input('Route', array(
				'type' => 'select',
				'multiple' => 'checkbox',
				'value' => Set::extract('/ModuleRoute/Route/id', $this->request->data)
			))
		));

		$right = array(
			$this->Form->input('name'),
			$this->Html->tag('div', implode('', array(
				$this->Html->tag('div',
					implode('', array(
						$this->Form->label('plugin', $parts['module']),
						$this->Form->input('plugin', array(
							'label' => false,
							'div' => false,
							'class' => "ajaxSelectPopulate {url:{action:'getModules'}, target:'ModuleModule'}"
						)),
						$this->Form->input('module', array(
							'label' => false,
							'div' => false
						))
					)),
				array('class' => 'input select smaller')),
				$this->Form->input('active')
			)),array('class' => 'dynamic'))
		);

		echo $this->Html->tag('div', implode('', array(
			$this->Html->tag('div', $this->Design->tabs($tabs, $contents), array('class' => 'span9')),
			$this->Design->sidebar($right, __d('infinitas', 'Module'))
		)), array('class' => 'row-fluid form'));
	echo $this->Form->end();