<?php
class InfinitasPaymentsEvents extends AppEvents {

	public function onPluginRollCall(Event $Event) {
		return array(
			'name' => 'Payments',
			'description' => 'Request payments',
			'author' => 'Infinitas',
			'icon' => 'money',
			'dashboard' => array('plugin' => 'infinitas_payments', 'controller' => 'infinitas_payment_methods', 'action' => 'dashboard')
		);
	}

	public function onRequireComponentsToLoad(Event $Event) {
		return array(
			'InfinitasPayments.InfinitasPayments'
		);
	}
}
