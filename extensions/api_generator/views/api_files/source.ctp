<?php
/**
 * Browse view.  Shows file listings and provides links to obtaining api docs from a file
 * Doubles as an ajax view by omitting certain tags when params['isAjax'] is set.
 */
$this->pageTitle = $currentPath;
?>
<?php if (!$this->params['isAjax']): ?>
<h1 class="breadcrumb"><?php echo $this->element('breadcrumb'); ?></h1>
<ul id="file-list">
<?php endif; ?>

	<li class="folder previous-folder">
		<?php echo $html->link(__d('api_generator', 'Up one folder', true), array('action' => 'source', $previousPath)); ?>
	</li>
<?php foreach ($dirs as $dir): ?>
	<li class="folder">
		<?php echo $html->link($dir, array('action' => 'source', $currentPath . '/' . $dir)); ?>
	</li>
<?php endforeach; ?>
<?php if (!empty($files)): ?>
<?php foreach ($files as $file): ?>
	<li class="file">
		<?php echo $html->link($file, array('action' => 'view_file', $currentPath . '/' . $file)); ?>
	</li>
<?php endforeach; ?>
<?php else: ?>
	<li class="file">
		<span><?php __d('api_generator', 'No files'); ?></span>
	</li>
<?php endif; ?>

<?php if (!$this->params['isAjax']): ?>
</ul>
<?php endif; ?>