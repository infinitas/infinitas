<?php
$dashboardIcons = array(
	array(
		'name' => 'Providers',
		'description' => 'Manage the payment providers',
		'icon' => 'envelope',
		'dashboard' => array(
			'controller' => 'infinitas_payment_methods',
			'action' => 'index'
		)
	),
	array(
		'name' => 'Transactions',
		'description' => 'View and manage transactions',
		'icon' => 'envelope',
		'dashboard' => array(
			'controller' => 'infinitas_payment_logs',
			'action' => 'index'
		)
	)
);

$dashboardIcons = $this->Menu->builDashboardLinks($dashboardIcons, 'infinitas_payments');
echo $this->Design->dashboard($this->Design->arrayToList(current((array)$dashboardIcons), 'icons'), __d('infinitas_payments', 'Payments'));
