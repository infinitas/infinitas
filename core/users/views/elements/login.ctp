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

	echo $this->Html->link(
		__('Login', true),
		array(
			'plugin' => 'users',
			'controller' => 'users',
			'action' => 'login',
			'TB_inline?height=185&width=600&inlineId=login-box'
		),
		array(
			'class' => 'niceLink thickbox'
		)
	);
?>
<div id="login-box">
	<div class="siteLogin">
		<?php
			echo $this->Form->create('User', array('url' => array('plugin' => 'users', 'controller' => 'users', 'action' => 'login')));
				echo sprintf('<b>%s</b>', __('Members Login', true));
				echo $this->Form->input('username', array('label' => false, 'value' => __('Username', true)));
				echo $this->Form->input('password', array('label' => false, 'value' => __('Password', true)));
				echo $this->Form->submit('Login', array('class' => 'niceLink'));
			echo $this->Form->end();
			
			$links = array('');
			$links[] = $this->Html->link(
				__('Forgot your password', true),
				array(
					'plugin' => 'users',
					'controller' => 'users',
					'action' => 'forgot_password'
				)
			);
			$links[] = $this->Html->link(
				__('Create an account', true),
				array(
					'plugin' => 'users',
					'controller' => 'users',
					'action' => 'register'
				)
			);

			echo implode('<br/>', $links);
		?>
	</div>
	<div class="oAuthLogin">
		<?php
			$providers = $this->Event->trigger('oauthProviders');
			foreach($providers['oauthProviders'] as $provider){
				if(isset($provider['element']) && isset($provider['config'])){
					echo $this->element((string)$provider['element'], (array)$provider['config']);
				}
			}
		?>
		<p><?php echo __('You may us the above logins if you would like to become a member, or your account is already linked.', true); ?></p>
	</div>
</div>
