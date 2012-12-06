<?php
echo $this->Form->input('ModuleConfig.feed', array(
	'type' => 'text',
	'label' => __d('feed', 'The name of the feed'),
	'placeholder' => __d('feed', 'my-feed')
));
echo $this->Form->input('ModuleConfig.event_before', array(
	'type' => 'checkbox',
	'label' => __d('feed', 'Run the before event'),
	'checked' => true
));
echo $this->Form->input('ModuleConfig.event_after', array(
	'type' => 'checkbox',
	'label' => __d('feed', 'Run the after event'),
	'checked' => true
));
