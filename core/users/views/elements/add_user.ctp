<fieldset>
	<h1><?php echo __('User Details', true); ?></h1><?php
	echo $this->Form->input('User.username');
	echo $this->Form->input('User.email');
	echo $this->Form->input('User.password', array('value' => ''));
	echo $this->Form->input('User.confirm_password', array('type' => 'password', 'value' => ''));
	echo $this->Form->input('User.active');?>
</fieldset>