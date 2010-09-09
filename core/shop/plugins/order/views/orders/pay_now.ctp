<h2>Please select your payment method</h2>
<?php
	foreach($paymentMethods as $payment){
		?><div class="paymentButtons">
			<h3><?php echo $payment; ?></h3>
		<?php
		echo $this->element('payment', array('plugin' => 'payment_'.$payment, 'order' => $order));
		?></div><?php
	}
?>