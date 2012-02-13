<div class="acos form">
<?php echo $this->Form->create('Aco');?>
	<fieldset>
 		<legend><?php printf(__('Admin Add %s'), __('Aco')); ?></legend>
	<?php
		echo $this->Form->input('parent_id');
		echo $this->Form->input('model');
		echo $this->Form->input('foreign_key');
		echo $this->Form->input('alias');
		echo $this->Form->input('lft');
		echo $this->Form->input('rght');
		echo $this->Form->input('Aro');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Delete'), array('action' => 'delete', $this->Form->value('Aco.id')), null, sprintf(__('Are you sure you want to delete # %s?'), $this->Form->value('Aco.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s'), __('Acos')), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s'), __('Acos')), array('controller' => 'acos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s'), __('Parent Aco')), array('controller' => 'acos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s'), __('Aros')), array('controller' => 'aros', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s'), __('Aro')), array('controller' => 'aros', 'action' => 'add')); ?> </li>
	</ul>
</div>