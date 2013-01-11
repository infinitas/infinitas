<?php
$config['InfinitasPayments'] = array(
	'form_url' => 'https://www.paypal.com/cgi-bin/webscr',
	'Provider' => array(
		'PayPalExpress' => array(
			'class' => 'PayPalExpress',
			'provider' => 'PayPal'
		)
	)
);
