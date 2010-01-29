<?php
/**
 * No class view file. Displayed when no class is found.
 *
 */
?>
<h2><?php __d('api_generator', 'No classes were found in the requested file'); ?></h2>
<p class="folder">
	<?php echo $html->link(__d('api_generator', 'Up one folder', true), array('action' => 'source', $previousPath)); ?>
</p>
