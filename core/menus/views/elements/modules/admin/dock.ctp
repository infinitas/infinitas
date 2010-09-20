<div id="dock" class="plugins">
	<div class="panel" style="display: none;">
		<div class="dashboard">
			<ul class="icons"><li><?php echo implode('</li><li>', $this->Menu->adminDashboard['core']); ?></li></ul>
		</div>
		<?php
			if(isset($this->Menu->adminDashboard['plugin'])){
				?>
					<div class="dashboard">
						<ul class="icons"><li><?php echo implode('</li><li>', $this->Menu->adminDashboard['plugin']); ?></li></ul>
					</div>
				<?php
			}
		?>
	</div>
	<a href="#" class="trigger"><?php echo __('Plugins', true); ?></a>
</div>