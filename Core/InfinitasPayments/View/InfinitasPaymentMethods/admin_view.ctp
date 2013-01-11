<div class="infinitasPaymentMethods view">
<h2><?php echo __('Infinitas Payment Method');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasPaymentMethod['InfinitasPaymentMethod']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Name'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasPaymentMethod['InfinitasPaymentMethod']['name']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Slug'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasPaymentMethod['InfinitasPaymentMethod']['slug']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Live'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasPaymentMethod['InfinitasPaymentMethod']['live']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Sandbox'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasPaymentMethod['InfinitasPaymentMethod']['sandbox']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Testing'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasPaymentMethod['InfinitasPaymentMethod']['testing']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Active'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasPaymentMethod['InfinitasPaymentMethod']['active']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Infinitas Payment Log Count'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasPaymentMethod['InfinitasPaymentMethod']['infinitas_payment_log_count']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasPaymentMethod['InfinitasPaymentMethod']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $infinitasPaymentMethod['InfinitasPaymentMethod']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('Edit %s'), __('Infinitas Payment Method')), array('action' => 'edit', $infinitasPaymentMethod['InfinitasPaymentMethod']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('Delete %s'), __('Infinitas Payment Method')), array('action' => 'delete', $infinitasPaymentMethod['InfinitasPaymentMethod']['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $infinitasPaymentMethod['InfinitasPaymentMethod']['id'])); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s'), __('Infinitas Payment Methods')), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s'), __('Infinitas Payment Method')), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s'), __('Infinitas Payment Logs')), array('controller' => 'infinitas_payment_logs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s'), __('Infinitas Payment Log')), array('controller' => 'infinitas_payment_logs', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php printf(__('Related %s'), __('Infinitas Payment Logs'));?></h3>
	<?php if (!empty($infinitasPaymentMethod['InfinitasPaymentLog'])):?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Infinitas Payment Method Id'); ?></th>
		<th><?php echo __('Token'); ?></th>
		<th><?php echo __('Transaction Id'); ?></th>
		<th><?php echo __('Transaction Type'); ?></th>
		<th><?php echo __('Transaction Fee'); ?></th>
		<th><?php echo __('Raw Request'); ?></th>
		<th><?php echo __('Raw Response'); ?></th>
		<th><?php echo __('Transaction Date'); ?></th>
		<th><?php echo __('Currency Code'); ?></th>
		<th><?php echo __('Total'); ?></th>
		<th><?php echo __('Tax'); ?></th>
		<th><?php echo __('Custom'); ?></th>
		<th><?php echo __('Status'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($infinitasPaymentMethod['InfinitasPaymentLog'] as $infinitasPaymentLog):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
		<tr<?php echo $class;?>>
			<td><?php echo $infinitasPaymentLog['id'];?></td>
			<td><?php echo $infinitasPaymentLog['infinitas_payment_method_id'];?></td>
			<td><?php echo $infinitasPaymentLog['token'];?></td>
			<td><?php echo $infinitasPaymentLog['transaction_id'];?></td>
			<td><?php echo $infinitasPaymentLog['transaction_type'];?></td>
			<td><?php echo $infinitasPaymentLog['transaction_fee'];?></td>
			<td><?php echo $infinitasPaymentLog['raw_request'];?></td>
			<td><?php echo $infinitasPaymentLog['raw_response'];?></td>
			<td><?php echo $infinitasPaymentLog['transaction_date'];?></td>
			<td><?php echo $infinitasPaymentLog['currency_code'];?></td>
			<td><?php echo $infinitasPaymentLog['total'];?></td>
			<td><?php echo $infinitasPaymentLog['tax'];?></td>
			<td><?php echo $infinitasPaymentLog['custom'];?></td>
			<td><?php echo $infinitasPaymentLog['status'];?></td>
			<td><?php echo $infinitasPaymentLog['created'];?></td>
			<td><?php echo $infinitasPaymentLog['modified'];?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'infinitas_payment_logs', 'action' => 'view', $infinitasPaymentLog['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'infinitas_payment_logs', 'action' => 'edit', $infinitasPaymentLog['id'])); ?>
				<?php echo $this->Html->link(__('Delete'), array('controller' => 'infinitas_payment_logs', 'action' => 'delete', $infinitasPaymentLog['id']), null, sprintf(__('Are you sure you want to delete # %s?'), $infinitasPaymentLog['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(sprintf(__('New %s'), __('Infinitas Payment Log')), array('controller' => 'infinitas_payment_logs', 'action' => 'add'));?> </li>
		</ul>
	</div>
</div>
