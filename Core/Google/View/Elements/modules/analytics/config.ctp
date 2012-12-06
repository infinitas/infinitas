<?php
echo $this->Form->input('ModuleConfig.code', array(
	'type' => 'text',
	'label' => __d('google', 'Analytics tracking code'),
	'placeholder' => 'UA-xxxxxxxx-x'
));