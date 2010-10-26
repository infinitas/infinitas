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

	echo $this->Form->create('EmailAccount');
        echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Outgoing', true); ?></h1><?php
			echo $this->Form->input('id');
			echo $this->Form->input('name');
			echo $this->Form->input('server');
			echo $this->Form->input('user_id', array('empty' => Configure::read('Website.empty_select')));
			echo $this->Form->input('username', array('autocomplet' => false));
			echo $this->Form->input('password', array('autocomplet' => false));
			echo $this->Form->input('email');
			?>
		</fieldset>
		<fieldset>
			<h1><?php echo __('Configuration', true); ?></h1><?php
			echo $this->Form->input('system');
			echo $this->Form->input('outgoing');
			echo $this->Form->input('ssl');?>
			<div class="input select smaller">
				<label for=""><?php echo __('Connection Type', true); ?></label><?php
				echo $this->Form->input('type', array('type' => 'select', 'class' => 'required', 'div' => false, 'label' => false, 'options' => $types, 'empty' => Configure::read('Website.empty_select')));
				echo $this->Form->input('port');
				echo $this->Form->input('readonly', array('options' => array(0 => 'Read and Write', 1 => 'Read only'), 'empty' => Configure::read('Website.empty_select'))); ?>
			</div>
			<?php
				$params = array(
					'plugin' => 'assets',
					'message' => __('The following is only a guide, please check your server as it may be different', true)
				);
				echo $this->element('messages/notice', $params);
			?>
			<table style="width: 300px; margin: auto;">
				<tr><th><?php echo __('Port', true); ?></th><th><?php echo __('Use', true); ?></th></tr>
				<tr><td>110</td><td><?php echo __('Standard POP3 port / MMP POP3 Proxy', true); ?></td></tr>
				<tr><td>992</td><td><?php echo __('POP3 over SSL', true); ?></td></tr>
				<tr><td>143</td><td><?php echo __('Standard IMAP4 port / MMP IMAP Proxy', true); ?></td></tr>
				<tr><td>993</td><td><?php echo __('IMAP over SSL or MMP IMAP Proxy over SSL', true); ?></td></tr>
				<tr><td> 25</td><td><?php echo __('Standard SMTP port', true); ?></td></tr>
				<tr><td>465</td><td><?php echo __('Standard SMTP port', true); ?></td></tr>
			</table>
		</fieldset>
	<?php echo $this->Form->end(); ?>