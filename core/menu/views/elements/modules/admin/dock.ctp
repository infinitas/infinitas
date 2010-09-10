<?php
	$plugins = $this->Event->trigger('pluginRollCall');
	$plugins = array_filter($plugins['pluginRollCall']);

	$_info = array(
			'name' => '',
			'icon' => 'core/infinitas_thumb.png',
			'dashboard' => '',
			'menus' => array(

			)
	);

	$_icons = array();

	foreach($plugins as $name => $info) {
		$info = array_merge($_info, $info);
		if(empty($info['name'])){
			$info['name'] = __(prettyName($name), true);
		}

		if(empty($info['dashboard'])) {
			$info['dashboard'] = array(
				'plugin' => strtolower($name),
				'controller' => false,
				'action' => false
			);
		}

		$_icons[] = $this->Html->link(
			$info['name'],
			$info['dashboard'],
			array(
				'title' => $info['name'],
				'escape' => false,
				'style' => 'background-image: url('.$info['icon'].');'
			)
		);
	}
?>

<!--<div id="pluginMenu" class="grid_16 center">
	<div id="pluginDock"><?php echo implode('', $_icons); ?></div>
</div>
<div class="clear"></div>-->
<div id="dock" class="plugins">
	<div class="panel" style="display: none;">
		<div class="dashboard">
			<ul class="icons"><li><?php echo implode('</li><li>', $_icons); ?></li></ul>
		</div>
	</div>
	<a href="#" class="trigger">Plugins</a>
</div>