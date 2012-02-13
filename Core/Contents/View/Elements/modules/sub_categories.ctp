<div class="widget">
	<div class="widget-top round-top"></div>
	<h3><?php echo __d('contents', 'Sub Categories'); ?></h3>
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
			$links = array(__d('contents', 'No sub categories found'));
		}

		if(!empty($parentCategory['id'])) {
			$links[] = $this->Html->link(
				sprintf(__d('contents', 'Back to %s'), $parentCategory['title']),
				array(
					'plugin' => 'contents',
					'controller' => 'global_categories',
					'action' => 'view',
					'category' => $parentCategory['slug']
				)
			);
		}

		$exploreLink = !empty($this->params['category']) && 
			(($this->params['controller'] == 'global_categories' && $this->params['action'] != 'view') ||
			$this->params['controller'] != 'global_categories');

		if($exploreLink) {
			$links[] = $this->Html->link(
				__d('contents', 'Explore this category'),
				array(
					'plugin' => 'contents',
					'controller' => 'global_categories',
					'action' => 'view',
					'category' => $this->params['category']
				)
			);
		}

		$links[] = $this->Html->link(
			__d('contents', 'View all categories'),
			array(
				'plugin' => 'contents',
				'controller' => 'global_categories',
				'action' => 'index'
			)
		);

		echo $this->Design->arrayToList($links, array('div' => 'widget-content', 'ul' => 'arrow-list'));
	?>
</div>