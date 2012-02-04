<div class="contact">
	<?php
		echo $this->Form->create();
			echo $this->Form->input('name');
			echo $this->Form->input('email');
			echo $this->Form->input('query', array('type' => 'textarea'));
			echo $this->Form->hidden('om_non_nom');
			echo $this->Form->submit('Submit');
		echo $this->Form->end();
	?>
</div>
