<h1><?php echo sprintf('Feeds available on %s', Configure::read('Website.name')); ?></h1>
<div id="accordion">
	<?php
		foreach($feeds as $feed){
			?>
				<h3><a href="#"><?php echo $feed['Feed']['name']; ?></a></h3>
				<div>
					<?php
						echo $feed['Feed']['description'];

						echo $this->Html->link(
							__('Subscribe', true),
							array(
								'plugin' => 'feed',
								'controller' => 'feeds',
								'action' => 'get_feed',
								'id' => $feed['Feed']['id'],
								'slug' => $feed['Feed']['slug'],
								'ext' => 'rss'
							)
						);
					?>
					<ul>
						<?php
							foreach($feed['FeedItem'] as $item){
								?><li title="<?php echo strip_tags($item['description']); ?>"><?php echo $item['name']; ?></li><?php
							}
						?>
					</ul>
				</div>
			<?php
		}
	?>
</div>