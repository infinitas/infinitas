<?php
    echo $this->Form->create('Post', array('url' => array('plugin' => 'blog', 'controller' => 'posts', 'action' => 'add')));
        echo $this->Design->niceBox();
	        echo $this->Form->input('category_id', array('empty' => Configure::read('Website.empty_select')));
	        echo $this->Form->input('title', array('class' => 'title'));
			echo $this->Form->input('new_tags', array('label' => __('Tags', true), 'class'=>'title'));
			echo '<br/>';
	        echo $this->Core->wysiwyg('Post.body', 'Simple');
			echo $this->Form->input('active', array('type' => 'checkbox', 'checked' => true));
		    echo $this->Form->submit('Save', array('style' => 'float:right; clear:none;'));
        echo $this->Design->niceBoxEnd();
?>