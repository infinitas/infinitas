<fieldset>
	<h1><?php echo __('Address', true); ?></h1><?php
	echo $this->Form->hidden('Address.id');
	echo $this->Form->hidden('Address.plugin', array('value' => 'Projects'));
	echo $this->Form->hidden('Address.model', array('value' => 'Company'));
	echo $this->Form->hidden('Address.foreign_key');

	echo $this->Form->input('Address.name');
	echo $this->Form->input('Address.street');
	echo $this->Form->input('Address.city');
	echo $this->Form->input('Address.province');
	echo $this->Form->input('Address.postal');
	echo $this->Form->input('Address.country_id', array('empty' => Configure::read('Website.empty_select')));
	echo $this->Form->input('Address.continent_id', array('empty' => Configure::read('Website.empty_select'))); ?>
</fieldset>