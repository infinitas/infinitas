<div class="category_item">
    <div class="category_image" title="<?php echo $category['Category']['name']; ?>">
    	<?php
			$category['Category']['plugin'] = 'shop';
			$category['Category']['controller'] = 'categories';
			$category['Category']['action'] = 'index';
			$category['Category']['parent_id'] = $category['Category']['id'];
			$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'categories', 'data' => $category['Category']));

			echo $this->Html->link(
				$this->Shop->getImage(
					$category,
					array(
						'width' => '100px',
						'title' => $category['Category']['name'],
						'alt' => $category['Category']['name']
					)
				),
				current($eventData['slugUrl']),
				array(
					'escape' => false
				)
			);
    	?>
    </div>
    <div class="category_name">
    	<?php
			echo $this->Html->link(
				$category['Category']['name'],
				current($eventData['slugUrl'])
			);
    	?>
    </div>
</div>