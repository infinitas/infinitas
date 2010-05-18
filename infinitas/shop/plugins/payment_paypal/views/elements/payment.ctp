<?php
	// https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_html_Appx_websitestandard_htmlvariables
	$paypal = Configure::read('Shop.Payment.Paypal');
	echo $this->Form->create('Paypal', array('url' => 'https://www.paypal.com/cgi-bin/webscr'));
		echo $this->Form->hidden('cmd', array('name' => 'cmd', 'value' => $paypal['cmd']));
		echo $this->Form->hidden('no_note', array('name' => 'no_note', 'value' => $paypal['no_note']));
		echo $this->Form->hidden('business', array('name' => 'business', 'value' => $paypal['email']));
		echo $this->Form->hidden('currency_code', array('name' => 'currency_code', 'value' => Configure::read('Currency.code')));
		echo $this->Form->hidden('return', array('name' => 'return', 'value' => Configure::read('Order.payment_address')));
		echo $this->Form->hidden('notify_url', array('name' => 'notify_url', 'value' => Configure::read('Order.notify_url')));
		echo $this->Form->hidden('amount', array('name' => 'amount', 'value' => number_format($order['Order']['total'], 2))); // excluding
		echo $this->Form->hidden('item_name', array('name' => 'item_name', 'value' => Configure::read('Website.name').' Sales')); // description of the payment
		echo $this->Form->hidden('quantity', array('name' => 'quantity', 'value' => $order['Order']['item_count'])); // number of items in total
		echo $this->Form->hidden('shipping', array('name' => 'shipping', 'value' => number_format($order['Order']['shipping'], 2))); // shipping cost
		echo $this->Form->hidden('tax_cart', array('name' => 'tax_cart', 'value' => number_format(($order['Order']['grand_total'] / 100) * Configure::read('Shop.vat_rate'), 2))); // tax amount
		echo $this->Form->hidden('address_override', array('name' => 'address_override', 'value' => $order['Address']['address'])); // users address
		echo $this->Form->hidden('currency_ code', array('name' => 'currency_ code', 'value' => Configure::read('Currency.code'))); // currency code
		echo $this->Form->hidden('invoice', array('name' => 'invoice', 'value' => $order['Order']['id'])); // order number
		echo $this->Form->hidden('shopping_url', array('name' => 'shopping_url', 'value' => 'http://'.env('SERVER_NAME').$this->webroot)); // continue shopping url
		echo $this->Form->submit('Paypal');
	echo '</form>';
?>
