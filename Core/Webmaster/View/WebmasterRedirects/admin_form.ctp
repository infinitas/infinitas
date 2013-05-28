<?php
	/**
	 * @brief Add some documentation for this admin_add form.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link    http://infinitas-cms.org/Webmaster
	 * @package	Webmaster.views.admin_add
	 * @license	http://infinitas-cms.org/mit-license The MIT License
	 * @since   0.9b1
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	echo $this->Form->create();
		echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Webmaster redirects'); ?></h1><?php
				echo $this->Form->input('id');
				echo $this->Form->input('redirect_permanent');
				echo $this->Form->input('ignore');
				echo $this->Infinitas->wysiwyg('WebmasterRedirect.url');
				echo $this->Infinitas->wysiwyg('WebmasterRedirect.redirect_to');
				echo $this->Infinitas->wysiwyg('WebmasterRedirect.redirect_message');
			?>
		</fieldset>

		<fieldset>
			<h1><?php echo __('Configuration'); ?></h1><?php
		?>
		</fieldset><?php
	echo $this->Form->end();
