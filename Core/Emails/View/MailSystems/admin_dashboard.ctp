<?php
$dashboardIcons = array(
	array(
		'name' => 'Accounts',
		'description' => 'Manage your email accounts',
		'dashboard' => array(
			'controller' => 'email_accounts',
			'action' => 'index'
		)
	)
);
$dashboardIcons = $this->Menu->builDashboardLinks($dashboardIcons, 'email_dashbaord');
echo $this->Design->dashboard($this->Design->arrayToList(current((array)$dashboardIcons), 'icons'), __d('emails', 'Email Manager'), array(
	'info' => Configure::read('Email.info.general')
));

if (empty($accounts)) {
	echo $this->Design->alert(__d('emails', 'You do not have any accounts set up'));
	return;
}

foreach ($accounts as &$account) {
	$_url = $this->Event->trigger('Emails.slugUrl', array('type' => 'inbox', 'data' => $account));
	$_url = current($_url['slugUrl']);
	unset($_url['plugin']);
	$account = array(
		'name' => $account['EmailAccount']['name'],
		'description' => $account['EmailAccount']['email'],
		'dashboard' => $_url
	);
}

$accounts = $this->Menu->builDashboardLinks($accounts, 'accounts_' . AuthComponent::user('id'));
echo $this->Design->dashboard($this->Design->arrayToList(current((array)$accounts), 'icons'), __d('emails', 'Your Accounts'), array(
	'info' => Configure::read('Email.info.accounts')
));
