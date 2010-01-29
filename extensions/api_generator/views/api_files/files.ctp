<?php
/**
 * Recursive Listing of all allowed files.
 *
 */
$this->pageTitle = __d('api_generator', 'All Files', true);
?>
<h1><?php __d('api_generator', 'All files')?></h1>

<ul id="file-list">
<?php if (!empty($files)): ?>
<?php foreach ($files as $file): ?>
	<li class="file">
		<?php echo $apiDoc->fileLink($file); ?>
	</li>
<?php endforeach; ?>
<?php else: ?>
	<li class="file">
		<?php __d('api_generator', 'No files'); ?>
	</li>
<?php endif; ?>
</ul>