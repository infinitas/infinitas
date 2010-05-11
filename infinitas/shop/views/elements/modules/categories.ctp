<?php
	if(!isset($categories) || empty($categories)){
		$categories = ClassRegistry::init('Shop.ShopCategory')->getCategories();

		if(empty($categories)){
			echo __('No categories setup', true);
			return;
		}
	}

	$out = '<ul>';
		foreach($categories as $category){
			$category['ShopCategory']['plugin'] = 'shop';
			$category['ShopCategory']['controller'] = 'categories';
			$category['ShopCategory']['action'] = 'index';
			$category['ShopCategory']['plugin'] = 'shop';

			$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'categories', 'data' => $category['ShopCategory']));
			$out .= '<li>'.$this->Html->link(
				$category['ShopCategory']['name'],
				current($eventData['slugUrl'])
			).'</li>';
		}
	$out .= '</ul>';

	echo $out,
	$this->Html->link(
		__('All Categories', true),
		array(
			'plugin' => 'shop',
			'controller' => 'categories',
			'action' => 'index'
		)
	), '<br/>',
	$this->Html->link(
		__('All Products', true),
		array(
			'plugin' => 'shop',
			'controller' => 'products',
			'action' => 'index'
		)
	), '<br/>',
	$this->Html->link(
		__('All Specials', true),
		array(
			'plugin' => 'shop',
			'controller' => 'specials',
			'action' => 'index'
		)
	), '<br/>',
	$this->Html->link(
		__('All Spotlights', true),
		array(
			'plugin' => 'shop',
			'controller' => 'spotlights',
			'action' => 'index'
		)
	);
?>