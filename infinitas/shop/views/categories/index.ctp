<?php
	$back['url'] = array(
		'plugin' => 'shop',
		'controller' => 'categories',
		'action' => 'index'
	);
	$back['category'] = __('Index', true);
	if(!empty($currentCategory['Category']['id'])){
		$currentCategory['Category']['plugin'] = 'shop';
		$currentCategory['Category']['controller'] = 'categories';
		$currentCategory['Category']['action'] = 'index';
		$currentCategory['Category']['parent_id'] = $currentCategory['Category']['id'];
		$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'categories', 'data' => $currentCategory['Category']));

		$back['url'] = current($eventData['slugUrl']);
		$back['category'] = $currentCategory['Category']['name'];
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
				<h2 class="fade"><?php __('Categories'); ?></h2><?php
				foreach($categories as $category){
					echo $this->element('category', array('plugin' => 'shop', 'category' => $category));
				} ?>
			</div>
		<?php
	}
	if(!empty($products)){
		?>
			<div>
				<h2 class="fade"><?php __('Products'); ?></h2><?php
					foreach($products as $product){
						echo $this->element('product', array('plugin' => 'shop', 'product' => $product));
					}

					$slug = isset($product['ProductCategory'][0]['slug']) ? $product['ProductCategory'][0]['slug'] : 'missing-category';
					$id   = isset($product['ProductCategory'][0]['id']) ? $product['ProductCategory'][0]['id'] : 'missing-category';
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
	if(!empty($specials)){
		?>
			<div><?php
				echo $this->element('specials', array('plugin' => 'shop', 'specials' => $specials));?>
			</div>
		<?php
	}
?>