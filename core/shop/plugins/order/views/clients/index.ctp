<h2 class="fade"><?php __('My Account'); ?></h2>
<?php
	if(!empty($pendingOrders)){
		$here = $this->Html->link(
			__('Here', true),
			array(
				'plugin' => 'order',
				'controller' => 'orders',
				'action' => 'pay'
			)
		);
		?>
			<div class="notice">
				<p><?php echo sprintf(__('You have %s pending order(s), click %s to pay them now', true), count($pendingOrders), $here);?></p>
			</div>
		<?php
	}
?>
<p>
	<?php
		$here = $this->Html->link(
			__('Here', true),
			array(
				'plugin' => 'order',
				'controller' => 'orders',
				'action' => 'index'
			)
		);
		echo sprintf(__('View the status of your orders by clicking %s', true), $here);
	?>
</p>
<p>
	<?php
		$here = $this->Html->link(
			__('Here', true),
			array(
				'plugin' => 'order',
				'controller' => 'clients',
				'action' => 'addresses'
			)
		);
		echo sprintf(__('You can manage your addresses for delivery by clicking %s', true), $here);
	?>
</p>