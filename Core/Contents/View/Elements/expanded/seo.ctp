<?php
	if(empty($data['keyword_density']) && empty($data['body'])) {
		return false;
	}
	
	$title = empty($title) ? __d('contents', 'SEO') : $title;
?>
<div class="seo">
	<?php echo sprintf('<span>%s</span>', $title); ?>
	<table>
		<tr>
			<td><?php echo __d('contents', 'KW densitty'); ?>&nbsp;</td>
			<td><?php echo sprintf('%s %%', $data['keyword_density']); ?>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo __d('contents', 'Word Count'); ?>&nbsp;</td>
			<td><?php echo count(explode(' ', strip_tags($data['body']))); ?>&nbsp;</td>
		</tr>
		<tr>
			<td colspan="100"><?php echo $data['meta_keywords']; ?>&nbsp;</td>
		</tr>
	</table>
</div>