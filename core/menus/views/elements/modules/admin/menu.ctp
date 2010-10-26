<div id="menucontainer" class="grid_16 center">
	<div id="menunav">
		<ul>
			<?php
				if(isset($pluginInfo['pluginRollCall'][$this->plugin]['icon'])){
					echo '<li class="icon">'.$this->Html->image(DS . $this->plugin . DS . 'img' . DS . 'icon.png').'</li>';
				}
			?>
			<li><?php echo implode('</li><li>', $this->Menu->builAdminMenu()); ?></li>
		</ul>
		<?php echo $this->Html->image('/users/img/logout.png', array('url' => '/admin/logout', 'class' => 'logout-link')); ?>
	</div>
</div>
<div class="clear"></div>