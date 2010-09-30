<?php
    /**
	 * View to create and edit global categories
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link http://infinitas-cms.org
     * @package Infinitas.categories
     * @subpackage Infinitas.categories.admin_form
     * @license http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since 0.5a
	 * 
	 * @author dogmatic69
     */

	echo $this->Form->create('Category', array('action' => 'edit'));
		echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Category', true); ?></h1><?php
			echo $this->Form->input('id');
			echo $this->Form->input('title', array('class' => 'title'));
			echo $this->Infinitas->wysiwyg('Category.description'); ?>
		</fieldset>
		<fieldset>
			<h1><?php echo __('Config', true); ?></h1><?php
			echo $this->Form->input('parent_id');
			echo $this->Form->input('group_id', array('label' => __('Min Group', true), 'empty' => __('Public', true)));
			echo $this->Form->input('active'); ?>
		</fieldset>
		<?php
	echo $this->Form->end();
?>