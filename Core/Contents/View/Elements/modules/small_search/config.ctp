<?php
echo $this->Form->input('ModuleConfig.title', array(
	'type' => 'input',
	'label' => __d('contents', 'Module title'),
	'placeholder' => __d('contents', 'Blank for no title')
));
