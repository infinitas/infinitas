<?php
	if(!InfinitasPlugin::loaded('ViewCounter') || empty($model) || empty($data['id'])) {
		return false;
	}
	
	$title = empty($title) ? __d('contents', 'Views') : $title;
?>
<div class="chart">
	<?php 
		echo sprintf('<span>%s</span>', $title),
		$this->ModuleLoader->loadDirect(
			'ViewCounter.quick_view', 
			array('model' => $model, 'foreignKey' => $data['id'])
		); 
	?>
</div>