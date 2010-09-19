<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
?>
<div class="login-form">
	<?php
		if(!$this->Session->read('Auth.User.id')){
			echo $this->Form->create('User', array('url' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'login')));
				echo sprintf('<b>%s</b>', __('Members Login', true));
				echo $this->Form->input('username', array('label' => false, 'div' => false, 'value' => __('Username', true)));
				echo $this->Form->input('password', array('label' => false, 'div' => false, 'value' => __('Password', true)));
				echo $this->Form->submit('Login', array('div' => false, 'class' => 'niceLink'));
			echo $this->Form->end();
		}
		else{
			?><div class="loggedIn"><?php
				echo sprintf(__('Welcome back to %s', true), Configure::read('Website.name'));
				echo $this->Html->link(__('Help', true), array('plugin' => 'cms', 'controller' => 'content', 'action' => 'index'), array('class' => 'niceLink'));
				echo $this->Html->link(__('Logout', true), array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout'), array('class' => 'niceLink'));
			?></div><?php
		}
	?>
</div>
<div class="login-links">
	<?php
		if(!$this->Session->read('Auth.User.id')){
			echo $this->Html->link(
				__('Forgot your password', true),
				array(
					'plugin' => 'users',
					'controller' => 'users',
					'action' => 'forgot_password'
				)
			), '<br/>',
			$this->Html->link(
				__('Create an account', true),
				array(
					'plugin' => 'users',
					'controller' => 'users',
					'action' => 'register'
				)
			);
		}
		else{
			echo $this->Html->link(
				__('Manage Your profile', true),
				array(
					'plugin' => 'users',
					'controller' => 'users',
					'action' => 'view',
					$this->Session->read('Auth.User.id')
				)
			), '<br/>',
			$this->Html->link(
				__('Find out the latest News', true),
				'/'
			);
		}
	?>
</div>