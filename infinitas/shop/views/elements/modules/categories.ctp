<?php
	$categories = ClassRegistry::init('Shop.ShopCategory')->getCategories();

	if(empty($categories)){
		echo __('No categories setup', true);
		return;
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

	echo $out;
?>