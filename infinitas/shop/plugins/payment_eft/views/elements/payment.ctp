<?php
	$eft = Configure::read('Shop.Payment.Eft');
	echo $this->Form->create('Eft', array('url' => '/cms/news-item/1-e-f-t-payment-details'));
		echo $this->Form->button($this->Html->image('/payment_eft/img/eft.png'));
	echo '</form>';
?>