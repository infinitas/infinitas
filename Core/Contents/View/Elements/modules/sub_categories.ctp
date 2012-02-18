<div class="widget">
	<div class="widget-top round-top"></div>
	<h3><?php echo __d('contents', 'Sub Categories'); ?></h3>
	<?php
		if(!empty($subCategory)) {
			$links = array();
			foreach($subCategory as $category) {
				$url = $this->Event->trigger('Contents.slugUrl', array('type' => 'category', 'data' => array('GlobalCategory' => $category)));
				$links[] = $this->Html->link($category['title'], current($url['slugUrl']));
			}
		}
		
		if(empty($links)) {
			$links = array(__d('contents', 'No sub categories found'));
		}

		if(!empty($parentCategory['id'])) {
			$url = $this->Event->trigger('Contents.slugUrl', array('type' => 'category', 'data' => array('GlobalCategory' => $parentCategory)));
			$links[] = $this->Html->link(
				sprintf(__d('contents', 'Back to %s'), $parentCategory['title']),
				current($url['slugUrl'])
			);
		}

		$exploreLink = !empty($this->request->params['category']) && 
			(($this->request->params['controller'] == 'global_categories' && $this->request->params['action'] != 'view') ||
			$this->request->params['controller'] != 'global_categories');

		if($exploreLink) {
			$url = $this->Event->trigger('Contents.slugUrl', array('type' => 'category', 'data' => array('GlobalCategory' => array('slug' => $this->request->params['slug']))));
			$links[] = $this->Html->link(
				__d('contents', 'Explore this category'),
				current($url['slugUrl'])
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