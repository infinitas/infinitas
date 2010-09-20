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
		__('logout', true),
		array(
			'plugin' => 'users',
			'controller' => 'users',
			'action' => 'logout'
		),
		array(
			'class' => 'niceLink'
		)
	);
?>
<div id="login-box">
	<div class="siteLogout">
		<?php
			echo sprintf(__('Welcome back to %s', true), Configure::read('Website.name'));
			echo $this->Html->link(__('Logout', true), array('plugin' => 'users', 'controller' => 'users', 'action' => 'logout'), array('class' => 'niceLink'));
		?>
	</div>
	<div class="login-links">
		<?php
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
				__('See whats going on', true),
				array(
					'plugin' => 'feed',
					'controller' => 'feeds',
					'action' => 'index'
				)
			);
		?>
	</div>
</div>
