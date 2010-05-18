<?php
	$netcash = Configure::read('Shop.Payment.Netcash');
	echo $this->Form->create('Netcash', array('url' => 'https://gateway.netcash.co.za/vvonline/ccnetcash.asp'));
		echo $this->Form->hidden('m_1', array('name' => 'm_1', 'value' => $netcash['username'])); //un
		echo $this->Form->hidden('m_2', array('name' => 'm_2', 'value' => $netcash['password'])); //pw
		echo $this->Form->hidden('m_3', array('name' => 'm_3', 'value' => $netcash['pin'])); //pin
		echo $this->Form->hidden('p1', array('name' => 'p1', 'value' => $netcash['code'])); // code
		echo $this->Form->hidden('p3', array('name' => 'p3', 'value' => Configure::read('Website.name').' Sales')); // shop
		echo $this->Form->hidden('Budget', array('name' => 'Budget', 'value' => $netcash['budget'])); // budget
		echo $this->Form->hidden('p2', array('name' => 'p2', 'value' => $order['Order']['id'].'_'.time())); // transaction id
		echo $this->Form->hidden('p4', array('name' => 'p4', 'value' => number_format($order['Order']['grand_total'], 2))); // total
		echo $this->Form->hidden('m_4', array('name' => 'm_4', 'value' => $order['Order']['id'])); // order id
		echo $this->Form->hidden('m_5', array('name' => 'm_5', 'value' => $order['Order']['user_id'])); // user id
		echo $this->Form->hidden('m_9', array('name' => 'm_9', 'value' => $order['User']['email'])); // email address
		echo $this->Form->button($this->Html->image('/payment_netcash/img/logo.gif'));
	echo '</form>';
?>