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

	echo $this->Form->create('Module');
		echo $this->Infinitas->adminEditHead();

		$parts = array(
			'heading' => __d('modules', 'Menu Item'),
			'toggle' => __d('modules', 'Toggle'),
			'module' => __d('modules', 'Module File'),
			'where' => __d('modules', 'Where Module should show'),
			'selected' => isset($this->data['Route']) ? $this->data['Route'] : 0,
			'licence' => __d('module', 'Licence Details')
		);
		
		$tabs = array(
			__d('modules', 'Module Config'),
			__d('modules', 'Display To'),
			__d('modules', 'License Info')
		);

		$content = array();
		$content[0] = <<<TAB1
			{$this->Form->input('id')}
			{$this->Form->input('name')}
			<div class="dynamic">
				<div class="input select smaller">
					<label for="ModulePlugin">{$parts['module']}</label>
					{$this->Form->input('plugin', array('label' => false, 'div' => false, 'class' => "ajaxSelectPopulate {url:{action:'getModules'}, target:'ModuleModule'}"))}
					{$this->Form->input('module', array('label' => false, 'div' => false, 'empty' => __(Configure::read('Website.empty_select'))))}
				</div>
				{$this->Form->input('config', array('class' => 'title'))}
				{$this->Form->input('active')}
			</div>
			<div class="static">
				{$this->Form->input('show_heading')}
				{$this->Form->input('content', array('class' => 'title'))}
			</div>
TAB1;

		$content[1] = $this->Form->input('group_id', array('empty' => Configure::read('Website.empty_select'))) .
			$this->Form->input('theme_id', array('empty' => __('All Themes'))) .
			$this->Form->input('position_id', array('empty' => Configure::read('Website.empty_select'))) .
			$this->Form->input('Route', array('type' => 'select', 'multiple' => 'checkbox', 'selected' => $parts['selected']));

		$content[2] = $this->Form->input('author') . $this->Form->input('url') .
			$this->Form->input('update_url') . $this->Form->input('licence');
		
		echo $this->Design->tabs($tabs, $content);
	echo $this->Form->end();