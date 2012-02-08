<div class="widget">
	<div class="widget-top round-top"></div>
	<h3><?php echo __d('contents', 'Sub Categories', true); ?></h3>
	<?php
		if(!empty($subCategory)) {
			$links = array();
			foreach($subCategory as $category) {
				$links[] = $this->Html->link(
					$category['title'],
					array(
						'plugin' => 'contents',
						'controller' => 'global_categories',
						'action' => 'view',
						'category' => $category['slug']
					)
				);
			}
		}
		
		if(empty($links)) {
			$links = array(__d('contents', 'No sub categories found', true));
		}

		if(!empty($parentCategory['id'])) {
			$links[] = $this->Html->link(
				sprintf(__d('contents', 'Back to %s', true), $parentCategory['title']),
				array(
					'plugin' => 'contents',
					'controller' => 'global_categories',
					'action' => 'view',
					'category' => $parentCategory['slug']
				)
			);
		}
		else {
			$links[] = $this->Html->link(
				__d('contents', 'View all categories', true),
				array(
					'plugin' => 'contents',
					'controller' => 'global_categories',
					'action' => 'index'
				)
			);
		}

		echo $this->Design->arrayToList($links, array('div' => 'widget-content', 'ul' => 'arrow-list'));
	?>
</div>