<?php
	$dashboardIcons = array(
		array(
			'name' => 'Accounts',
			'description' => 'Manage your email accounts',
			'dashboard' => array(
				'plugin' => 'emails',
				'controller' => 'email_accounts',
				'action' => 'index'
			)
		)
	);
	$dashboardIcons = $this->Menu->builDashboardLinks($dashboardIcons, 'email_dashbaord');

	if($accounts) {
		$accountIcons = array();
		foreach($accounts as $account){
			$_url = $this->Event->trigger('Emails.slugUrl', array('type' => 'inbox', 'data' => $account));
			$accountIcons[] = array(
				'name' => $account['EmailAccount']['name'],
				'description' => $account['EmailAccount']['email'],
				'dashboard' => current($_url['slugUrl'])
			);
		}

		$accountIcons = $this->Menu->builDashboardLinks($accountIcons, 'accounts_' . $this->Session->read('Auth.User.id'));
	}
?>
<div class="dashboard grid_16">
	<h1><?php echo __('Email Manager'); ?></h1>
	<?php echo $this->Design->arrayToList(current((array)$dashboardIcons), 'icons'); ?>
	<p class="info"><?php echo Configure::read('Email.info.general'); ?></p>
</div>

<div class="dashboard grid_8 half">
	<h1><?php echo __('Your Accounts'); ?></h1>
	<?php
		if(!empty($accountIcons)){
			echo $this->Design->arrayToList(current((array)$accountIcons), 'icons');
		}
	?>
	<p class="info"><?php echo Configure::read('Email.info.accounts'); ?></p>
</div>
<!--
<div class="dashboard grid_8 half">
	<h1><?php echo __('New Mails'); ?></h1>
	<ul class="icons"><li></li></ul>
</div> -->