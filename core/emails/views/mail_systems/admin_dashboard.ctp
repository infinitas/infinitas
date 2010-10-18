<?php
	$dashboardIcons = array(
		array(
			'name' => 'Accounts',
			'dashboard' => array(
				'plugin' => 'emails',
				'controller' => 'email_accounts',
				'action' => 'index'
			)
		)
	);
	$dashboardIcons = $this->Menu->builDashboardLinks($dashboardIcons, 'email_dashbaord');

	$accountIcons = array();
	foreach($accounts as $account){
		$_url = $this->Event->trigger('emails.slugUrl', array('type' => 'inbox', 'data' => $account));
		$accountIcons[] = array(
			'name' => $account['EmailAccount']['name'],
			'dashboard' => current($_url['slugUrl'])
		);
	}
	$accountIcons = $this->Menu->builDashboardLinks($accountIcons, 'accounts_'.$this->Session->read('Auth.User.id'));
?>
<div class="dashboard grid_16">
	<h1><?php __('Email Manager', true); ?></h1>
	<ul class="icons"><li><?php echo implode('</li><li>', current((array)$dashboardIcons)); ?></li></ul>
	<p class="info"><?php echo Configure::read('Email.info.general'); ?></p>
</div>

<div class="dashboard grid_8 half">
	<h1><?php echo __('Your Accounts', true); ?></h1>
	<?php
		if(!empty($accountIcons)){
			?><ul class="icons"><li><?php echo implode('</li><li>', current((array)$accountIcons)); ?></li></ul><?php
		}
	?>
	<p class="info"><?php echo Configure::read('Email.info.accounts'); ?></p>
</div>

<div class="dashboard grid_8 half">
	<h1><?php echo __('New Mails', true); ?></h1>
	<ul class="icons"><li></li></ul>
</div>