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
 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
 * @link		  http://infinitas-cms.org
 * @package	   management
 * @subpackage	management.views.menuItems.admin_add
 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
 */

echo $this->Form->create('EmailAccount');
	echo $this->Infinitas->adminEditHead();
	echo $this->Form->input('id');

	$tabs = array(
		__d('emails', 'Account'),
		__d('emails', 'Configuration')
	);
	$contents = array(
		implode('', array(
			$this->Form->input('name'),
			$this->Form->input('slug'),
			$this->Form->input('server'),
			$this->Form->input('port'),
			$this->Form->input('user_id', array('empty' => Configure::read('Website.empty_select'))),
			$this->Form->input('username', array('autocomplet' => false)),
			$this->Form->input('password', array('autocomplet' => false)),
			$this->Form->input('email')
		)),
		implode('', array(
			$this->Form->input('system'),
			$this->Form->input('outgoing'),
			$this->Form->input('ssl'),
			$this->Form->input('cron'),
			$this->Form->input('type', array(
				'type' => 'select',
				'class' => 'required',
				'div' => false,
				'options' => $types,
				'empty' => Configure::read('Website.empty_select'),
				'label' => __d('emails', 'Transport protocol')
			)),
			$this->Form->input('readonly', array(
				'options' => array(
					0 => __d('emails', 'Read and Write'),
					1 => __d('emails', 'Read only')
				),
				'empty' => Configure::read('Website.empty_select')
			))
		))
	);
	echo $this->Design->tabs($tabs, $contents);
echo $this->Form->end(); ?>
<br/>
<div class="dashboard">
	<h1><?php echo __d('emails', 'Email ports'); ?></h1>
	<?php
		echo $this->element('Assets.messages/warning', array(
			'message' => __d('emails', 'The following is only a guide, please check your server as it may be different')
		));
	?>
	<table style="width: 300px; margin: auto;">
		<thead>
			<tr><th><?php echo __d('emails', 'Port'); ?></th><th><?php echo __d('emails', 'Use'); ?></th></tr>
		</thead>
		<tbody>
			<tr><td>110</td><td><?php echo __d('emails', 'Standard POP3 port / MMP POP3 Proxy'); ?></td></tr>
			<tr><td>992</td><td><?php echo __d('emails', 'POP3 over SSL'); ?></td></tr>
			<tr><td>143</td><td><?php echo __d('emails', 'Standard IMAP4 port / MMP IMAP Proxy'); ?></td></tr>
			<tr><td>993</td><td><?php echo __d('emails', 'IMAP over SSL or MMP IMAP Proxy over SSL'); ?></td></tr>
			<tr><td> 25</td><td><?php echo __d('emails', 'Standard SMTP port'); ?></td></tr>
			<tr><td>465</td><td><?php echo __d('emails', 'Standard SMTP port'); ?></td></tr>
		</tbody>
	</table>
</div>