<?php $icons = $this->Menu->builDashboardLinks(); ?>
<div id="dock" class="plugins">
	<div class="panel" style="display: none;">
		<div class="dashboard">
			<ul class="icons"><li><?php echo implode('</li><li>', $icons['core']); ?></li></ul>
		</div>
		<?php
			if(isset($icons['plugin'])){
				?>
					<div class="dashboard">
						<ul class="icons"><li><?php echo implode('</li><li>', $icons['plugin']); ?></li></ul>
					</div>
				<?php
			}
		?>
	</div>
	<a href="#" class="trigger"><?php echo __('Plugins', true); ?></a>
</div>