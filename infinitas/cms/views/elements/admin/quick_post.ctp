<?php
	echo $this->Form->create('Content', array('url' => array('plugin' => 'cms', 'controller' => 'contents', 'action' => 'add')));
	echo $this->Form->input('layout_id');
	echo $this->Form->input('category_id');
	echo $this->Form->input('group_id', array('label' => __('Min Group', true)));
	echo $this->Form->input('title', array('class' => 'title'));
	echo $this->Core->wysiwyg('Content.body');
	echo $this->Form->input('active' );
	echo $this->Form->end(__('Submit', true));
?>