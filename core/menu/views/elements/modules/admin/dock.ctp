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

	foreach($plugins as $name => $info){
		$info = array_merge($_info, $info);

		if(empty($info['dashboard'])){
			$info['dashboard'] = array(
				'plugin' => strtolower($name),
				'controller' => false,
				'action' => false
			);
		}

		$_icons[] = $this->Html->link(
			$this->Html->image(
				$info['icon'],
				array(
					'alt' => __(prettyName($info['name']), true),
					'width' => '25px'
				)
			),
			$info['dashboard'],
			array(
				'title' => __(prettyName($info['name']), true),
				'escape' => false
			)
		);
	}
?>

<div id="pluginMenu" class="grid_16 center">
	<div id="pluginDock"><?php echo implode('', $_icons); ?></div>
</div>
<div class="clear"></div>