<?php
	if(empty($tagData['Tag']['description'])) {
		return;
	}
?>
<div class="tag-details">
	<h2><?php echo sprintf('Showing posts related to "%s"', $tagData['Tag']['name']); ?></h2>
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
					echo $tagData['Tag']['description'];
				}
			?>
		</div>
	</blockquote>
</div>