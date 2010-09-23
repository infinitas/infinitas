<?php
    /**
     * Management Config admin edit post.
     *
     * this page is for admin to manage the setup of the site
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

    echo $this->Form->create('Route');
        echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Route', true); ?></h1><?php
			echo $this->Form->input('id');
			echo $this->Form->input('name'); ?>
			<div class="static"><?php
				echo $this->Form->input('url'); ?>
			</div>
			<div class="dynamic"><?php
				$options = Configure::read('Routing.prefixes');
				$options = array_combine($options, $options);
				echo $this->Form->input('prefix', array('options' => $options, 'type' => 'select', 'empty' => __('None', true)));
				echo $this->element('route_select', array('plugin' => 'routes')); ?>
			</div><?php
			echo $this->Form->input('values');
			echo $this->Form->input('rules'); ?>
		</fieldset>
		<fieldset>
			<h1><?php echo __('Config', true); ?></h1>
			<div class="dynamic"><?php
				echo $this->Form->input('pass');
				echo $this->Form->input('force_backend');
				echo $this->Form->input('force_frontend'); ?>
			</div><?php
			echo $this->Form->input('active');
			echo $this->Form->input('theme_id');
			echo $this->Form->hidden('order_id', array('value' => 1)); ?>
		</fieldset>
	<?php echo $this->Form->end(); ?>