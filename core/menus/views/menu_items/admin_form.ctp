<?php
    /**
     * Management Modules admin edit post.
     *
     * this page is for admin to manage the menu items on the site
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
     * @subpackage    management.views.menuItems.admin_add
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

	echo $this->Form->create('MenuItem');
        echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Menu Item', true); ?></h1>
			<?php
				echo $this->Form->input('id');
				echo $this->Form->input('name');
				echo $this->Form->input('link', array('label' => __('External Link', true)));
				echo $this->Form->input('prefix');
				echo $this->element('route_select', array('plugin' => 'routes', 'model' => $this->params['models'][0])); ?>
				<div class="input select smaller">
					<label for=""><?php echo __('Parent Item', true); ?></label><?php
					echo $this->Form->input('menu_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => "pluginSelect {url:{action:'getParents'}, target:'MenuItemParentId'}", 'empty' => Configure::read('Website.empty_select')));
					echo $this->Form->input('parent_id', array('type' => 'select', 'div' => false, 'label' => false)); ?>
				</div>
		</fieldset>
		<fieldset>
			<h1><?php echo __('Config', true); ?></h1>
			<?php
				echo $this->Form->input('active');
				echo $this->Form->input('force_backend');
				echo $this->Form->input('force_frontend');
				echo $this->Form->input('group_id', array('empty' => Configure::read('Website.empty_select')));
				echo $this->Form->input('params', array('type' => 'textarea'));
				echo $this->Form->input('class');
			?>
		</fieldset>
	<?php echo $this->Form->end(); ?>