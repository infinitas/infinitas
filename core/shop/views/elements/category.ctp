<div class="category_item">
    <div class="category_image" title="<?php echo $category['ShopCategory']['name']; ?>">
    	<?php
			$category['ShopCategory']['plugin'] = 'shop';
			$category['ShopCategory']['controller'] = 'categories';
			$category['ShopCategory']['action'] = 'index';
			$category['ShopCategory']['parent_id'] = $category['ShopCategory']['id'];
			$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'categories', 'data' => $category['ShopCategory']));

			echo $this->Html->link(
				$this->Shop->getImage(
					$category,
					array(
						'width' => '100px',
						'title' => $category['ShopCategory']['name'],
						'alt' => $category['ShopCategory']['name']
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
				$category['ShopCategory']['name'],
				current($eventData['slugUrl'])
			);
    	?>
    </div>
</div>