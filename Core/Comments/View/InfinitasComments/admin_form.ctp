<?php
	echo $this->Form->create();
        echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Configuration'); ?></h1><?php
			echo $this->Form->input('id');
			echo $this->Form->input('class', array('readonly' => true));
			echo $this->Form->input('email', array('readonly' => true));
			echo $this->Form->input('rating', array('readonly' => true));
			echo $this->Form->input('points', array('readonly' => true));
			echo $this->Form->input('status', array('readonly' => true));
			echo $this->Form->input('created_', array('readonly' => true, 'value' => CakeTime::niceShort($this->request->data['InfinitasComment']['created'])));
			echo $this->Form->input('active');
			echo $this->Infinitas->wysiwyg('InfinitasComment.comment'); ?>
		</fieldset>
	<?php echo $this->Form->end(); ?>