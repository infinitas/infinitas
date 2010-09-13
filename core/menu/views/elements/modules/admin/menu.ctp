<?php
	$pluginInfo = $this->Event->trigger($this->plugin.'.pluginRollCall');
?>
<div id="menucontainer" class="grid_16 center">
	<div id="menunav">
		<ul>
			<?php
				if(isset($pluginInfo['pluginRollCall'][$this->plugin]['icon'])){
					echo '<li class="icon">'.$this->Html->image($pluginInfo['pluginRollCall'][$this->plugin]['icon']).'</li>';
				}
			?>
			<li><?php echo implode('</li><li>', $this->Menu->adminMenuItems); ?></li>
		</ul>
	</div>
</div>
<div class="clear"></div>