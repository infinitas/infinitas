<?php
	/**
	 * @brief Add some documentation for this admin_add form.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link		  http://infinitas-cms.org/users
	 * @package	   users.views.admin_add
	 * @license	   http://infinitas-cms.org/mit-license The MIT License
	 * @since 0.9b1
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	echo $this->Form->create('Group');
		echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Groups', true); ?></h1><?php
				echo $this->Form->input('id');
				echo $this->Form->input('name');
				echo $this->Form->input('parent_id', array('empty' => Configure::read('Website.empty_select')));
				echo $this->Infinitas->wysiwyg('Group.description');
			?>
		</fieldset>

		<fieldset>
			<h1><?php echo __('Configuration', true); ?></h1><?php
		?>
		</fieldset><?php
	echo $this->Form->end();
