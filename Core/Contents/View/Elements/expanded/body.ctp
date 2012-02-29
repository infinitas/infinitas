<?php 
	if(empty($data['body'])) {
		return false;
	}
	
	$truncate = empty($truncate) ? 200 : $truncate;
	$title = empty($title) ? __d('contents', 'Body') : $title;
?>
<div class="text">
	<?php 
		echo sprintf('<span>%s</span>', $title),
		sprintf('<p>%s</p>', $this->Text->truncate(strip_tags($data['body']), $truncate)); 
	?>
</div>