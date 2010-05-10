<?php
	$back['url'] = array(
		'plugin' => 'shop',
		'controller' => 'categories',
		'action' => 'index'
	);
	$back['category'] = __('Index', true);
	if(!empty($currentCategory['ShopCategory']['id'])){
		$currentCategory['ShopCategory']['plugin'] = 'shop';
		$currentCategory['ShopCategory']['controller'] = 'categories';
		$currentCategory['ShopCategory']['action'] = 'index';
		$currentCategory['ShopCategory']['parent_id'] = $currentCategory['ShopCategory']['id'];
		$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'categories', 'data' => $currentCategory['ShopCategory']));

		$back['url'] = current($eventData['slugUrl']);
		$back['category'] = $currentCategory['ShopCategory']['name'];
	}

	echo $this->Html->link(
		sprintf(__('Back to %s', true), $back['category']),
		$back['url'],
		array(
			'class' => 'categoryBack'
		)
	);

	if(!empty($categories)){
		?>
			<div>
				<h2 class="fade"><?php echo sprintf('%s (%s)',__('Categories', true), $currentCategory['ShopCategory']['name']); ?></h2><?php
				foreach($categories as $category){
					echo $this->element('category', array('plugin' => 'shop', 'category' => $category));
				} ?>
			</div><div class="clear"> </div>
		<?php
	}
	if(!empty($products)){
		?>
			<div>
				<h2 class="fade"><?php __('Products'); ?></h2><?php
					foreach($products as $product){
						echo $this->element('product', array('plugin' => 'shop', 'product' => $product));
					}

					$slug = isset($product['ShopCategory'][0]['slug']) ? $product['ShopCategory'][0]['slug'] : 'missing-category';
					$id   = isset($product['ShopCategory'][0]['id']) ? $product['ShopCategory'][0]['id'] : 'missing-category';
			    	echo $this->Html->link(
			    		'('.__('See all', true).')',
			    		array(
			    			'plugin' => 'shop',
			    			'controller' => 'products',
			    			'action' => 'index',
			    			'category' => $slug,
							'category_id' => $id
			    		),
			    		array(
			    			'class' => 'moreLink'
			    		)
			    	); ?>
			</div>
		<?php
	}
?>