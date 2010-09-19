<h1><?php echo sprintf('Feeds available on %s', Configure::read('Website.name')); ?></h1>
<div>
	<?php
		foreach($feeds as $feed){
			$rss = $this->Event->Trigger('feed.slugUrl', $feed);
			$rssLink = $this->Html->link(
				__('Subscribe', true),
				$rss['slugUrl']['feed']['rss']
			);
			$htmlLink = $this->Html->link(
				__('View', true),
				$rss['slugUrl']['feed']['html']
			);
			?><h3><?php echo $feed['Feed']['name']; ?><small><?php echo $rssLink, ' ', $htmlLink; ?></small></h3><?php
			echo $feed['Feed']['description'];			
		}
	?>
</div>