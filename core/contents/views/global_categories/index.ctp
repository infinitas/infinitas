<?php
	foreach($categories as &$category) {
		$event = $this->Event->trigger('contents.slugUrl', array('type' => 'category', 'data' => $category));
		$category['GlobalCategory']['url'] = Router::url($event['slugUrl']['contents'], true);
		$category['GlobalCategory']['title_link'] = $this->Html->link($category['GlobalCategory']['title'], $category['GlobalCategory']['url']);
		$category['GlobalCategory']['body'] = $this->Text->truncate(
			strip_tags($category['GlobalCategory']['body']), Configure::read('Contents.truncate_category')
		);
	}
	
	$this->set('categories', $categories);
	echo $this->GlobalContents->renderTemplate($globalLayoutTemplate);