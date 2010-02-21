<div class="acos form">
<?php echo $this->Form->create('Aco');?>
	<fieldset>
 		<legend><?php printf(__('Admin Add %s', true), __('Aco', true)); ?></legend>
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
<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Aco.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Aco.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Acos', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Acos', true)), array('controller' => 'acos', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Parent Aco', true)), array('controller' => 'acos', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Aros', true)), array('controller' => 'aros', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Aro', true)), array('controller' => 'aros', 'action' => 'add')); ?> </li>
	</ul>
</div>