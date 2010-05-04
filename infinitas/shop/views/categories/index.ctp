<?php
	if(!empty($categories)){
		?><h2 class="fade"><?php __('Categories'); ?></h2><?php
		foreach($categories as $category){
			echo $this->element('category', array('plugin' => 'shop', 'category' => $category));
		}

	}
	if(!empty($products)){
		?><h2 class="fade"><?php __('Products'); ?></h2><?php
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
    	);
	}
?>