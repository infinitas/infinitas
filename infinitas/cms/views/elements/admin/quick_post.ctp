<?php
		echo $this->Design->niceBox();
			if (!empty($categories) && !empty($layouts)) {
				echo $this->Form->create('Content', array('url' => array('plugin' => 'cms', 'controller' => 'contents', 'action' => 'add')));
					echo $this->Form->input('layout_id');
					echo $this->Form->input('category_id');
					echo $this->Form->input('group_id', array('label' => __('Min Group', true)));
					echo $this->Form->input('title', array('class' => 'title'));
					echo $this->Core->wysiwyg('Content.body', array('toolbar' => 'AdminBasic'));
					echo $this->Form->input('active' );
				echo $this->Form->end(__('Save', true));
			}
			else{
				if (empty($categories)){
					$links[] = sprintf(
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
				if (empty($layouts)){
					$links[] = sprintf(
						__('No layouts found, %s', true ),
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

				echo implode('<br/>', $links);
			}
        echo $this->Design->niceBoxEnd();
?>