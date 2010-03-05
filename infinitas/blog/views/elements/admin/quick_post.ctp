<?php
        echo $this->Design->niceBox();
	        if (!empty($categories)) {
			    echo $this->Form->create('Post', array('url' => array('plugin' => 'blog', 'controller' => 'posts', 'action' => 'add')));
			        echo $this->Form->input('category_id', array('empty' => Configure::read('Website.empty_select')));
			        echo $this->Form->input('title', array('class' => 'title'));
					echo $this->Form->input('new_tags', array('label' => __('Tags', true), 'class'=>'title'));
					echo '<br/>';
			        echo $this->Core->wysiwyg('Post.body', array('toolbar' => 'AdminBasic'));
					echo $this->Form->input('active', array('type' => 'checkbox', 'checked' => true));
			    echo $this->Form->submit('Save', array('style' => 'float:right; clear:none;'));
			}
			else{
				echo sprintf(
					__('No categories found, %s', true ),
					$this->Html->link(
						__('set some up', true ),
						array(
							'plugin' => 'blog',
							'controller' => 'categories',
							'action' => 'add'
						)
					)
				);
			}
        echo $this->Design->niceBoxEnd();
?>