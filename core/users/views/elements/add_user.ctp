<?php
	$alias = (isset($alias)) ? $alias : 'User';
?>
<fieldset>
	<h1><?php echo sprintf(__('%s Details', true), __(Inflector::humanize($alias), true)); ?></h1><?php
	echo $this->Form->input($alias . '.username');
	echo $this->Form->input($alias . '.email');
	echo $this->Form->input($alias . '.password', array('value' => ''));
	echo $this->Form->input($alias . '.confirm_password', array('type' => 'password', 'value' => ''));
	echo $this->Form->input($alias . '.active');?>
</fieldset>