<?php
echo $this->Form->input('ModuleConfig.view_all', array(
	'type' => 'text',
	'label' => __d('comments', 'Link text to view all comments'),
	'placeholder' => __d('comments', 'View all comments')
));
echo $this->Form->input('ModuleConfig.view_all', array(
	'type' => 'text',
	'label' => __d('comments', 'Text for required login'),
	'placeholder' => __d('comments', 'Please log in to leave a comment')
));