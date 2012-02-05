<?php
	if(empty($tagData['GlobalTag']['description'])) {
		return;
	}
?>
<div class="tag-details">
	<h2><?php echo sprintf('Showing posts related to "%s"', $tagData['GlobalTag']['name']); ?></h2>
	<blockquote>
		<div class="description">
			<?php
				if(!$tagData['Tag']['description']) {
					echo sprintf(
						'<p>%s</p>',
						'There does not seem to be a description for this tag'
					);
				}
				else {
					echo $tagData['GlobalTag']['description'];
				}
			?>
		</div>
	</blockquote>
</div>