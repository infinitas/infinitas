<?php
	$_url = array(
		'plugin' => $this->plugin,
		'controller' => false,
		'action' => false,
		'admin' => true,
		'prefix' => 'admin'
	);

	$menus = $this->Event->trigger($this->plugin.'.adminMenu');
	$items = isset($menus['adminMenu'][$this->plugin]['main']) ? $menus['adminMenu'][$this->plugin]['main'] : array();

	$items = array(
		'Home' => '/admin'
	) + $items;

	foreach($items as $name => $url){
		if(is_array($url)){
			$url = array_merge($_url, $url);
		}
		
		$options = array();
		if($this->here == Router::url($url)){
			$options = array(
				'class' => 'current'
			);
		}

		$_menus[] = $this->Html->link(
			$name,
			$url,
			$options
		);
	}
?>
<div id="menucontainer" class="grid_16 center">
	<div id="menunav">
		<ul>
			<li><?php echo implode('</li><li>', $_menus); ?></li>
		</ul>
	</div>
</div>
<div class="clear"></div>