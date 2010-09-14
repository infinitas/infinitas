<ul>
	<?php foreach($plugins as $plugin) { ?>
		<li>
			<h4><?php echo $plugin['name']?></h4>
			<div class="pluginInfo">
				<span class="author">
					<b>Author: </b>
					<?php
						if($plugin['website'] != '') {
							echo $this->Html->link($plugin['author'], 'http://' . $plugin['website']);
						}
						else {
							echo $plugin['author'];
						}
					?>
				</span>
				<span class="version">
					<b>Version: </b>
					<?php echo $plugin['version']; ?>
				</span>
			</div>
		</li>
	<?php } ?>
</ul>