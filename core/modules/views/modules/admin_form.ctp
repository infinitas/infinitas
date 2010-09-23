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
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       management
     * @subpackage    management.views.configs.admin_edit
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

    echo $this->Form->create('Module');
        echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Menu Item', true); ?><a href="#" onlcick="$('.dynamic').toggle(); $('.static').toggle();">Toggle</a></h1><?php
			echo $this->Form->input('id');
			echo $this->Form->input('name'); ?>
			<div class="dynamic">
				<div class="input select smaller">
					<label for="ModulePlugin"><?php echo __('Module File', true); ?></label><?php
					echo $this->Form->input('plugin', array('label' => false, 'div' => false, 'class' => "modulePuluginSelect {url:{action:'getModules'}, target:'ModuleModule'}"));
					echo $this->Form->input('module', array('label' => false, 'div' => false, 'empty' => __(Configure::read('Website.empty_select'), true))); ?>
				</div><?php
				echo $this->Form->input('config', array('class' => 'title'));
				echo $this->Form->input('active');?>
			</div>
			<div class="static"><?php
				echo $this->Form->input('show_heading');
				echo $this->Form->input('content', array('class' => 'title'));?>
			</div>
		</fieldset>
		<fieldset>
			<h1><?php echo __('Where Module should show', true); ?></h1><?php
			echo $this->Form->input('group_id', array('empty' => Configure::read('Website.empty_select')));
			echo $this->Form->input('theme_id', array('empty' => __('All Themes', true)));
			echo $this->Form->input('position_id', array('empty' => Configure::read('Website.empty_select')));
			$selected = isset($this->data['Route']) ? $this->data['Route'] : 0;
			echo $this->Form->input('Route', array('type' => 'select', 'multiple' => 'checkbox', 'selected' => $selected)); ?>
		</fieldset>
		<fieldset>
			<h1><?php echo __('Author Details', true); ?></h1><?php
			echo $this->Form->input('author');
			echo $this->Form->input('url');
			echo $this->Form->input('update_url');
			echo $this->Form->input('licence'); ?>
		</fieldset><?php
    echo $this->Form->end();
?>