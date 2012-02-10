{{%UNESCAPED}}
<div id="content">
	<div id="main-content">
		<?php echo $content_for_layout; ?>
	</div>
	<?php
		echo $this->element('design/sidebar', array('plugin' => 'bitcore'));

		if(!empty($relatedContent)) {
			echo '{{relatedContent}}';
		}
	?>
	<div class="clear"></div>
</div>