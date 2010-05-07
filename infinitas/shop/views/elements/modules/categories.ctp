<?php
	$Category = ClassRegistry::init('Shop.Category');
	$categories = $Category->getCategories();

	if(empty($categories)){
		echo __('No categories setup', true);
		return;
	}

	$out = '<ul>';
		foreach($categories as $category){
			$category['Category']['plugin'] = 'shop';
			$category['Category']['controller'] = 'categories';
			$category['Category']['action'] = 'index';
			$category['Category']['plugin'] = 'shop';

			$eventData = $this->Event->trigger('shop.slugUrl', array('type' => 'categories', 'data' => $category['Category']));
			$out .= '<li>'.$this->Html->link(
				$category['Category']['name'],
				current($eventData['slugUrl'])
			).'</li>';
		}
	$out .= '</ul>';

	echo $out;
?>