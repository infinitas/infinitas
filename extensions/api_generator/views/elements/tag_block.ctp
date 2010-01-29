<?php
/**
 * Element for rendering tag sets.
 *
 */
?>
<div class="tag-block">
<dl>
	<?php foreach ($tags as $name => $value): ?>
		<dt><?php echo $name; ?></dt>
		<?php 
		$lower = strtolower($name);
		if ($lower == 'link'):
			echo '<dd>' . $text->autoLink(h($value)) . '</dd>';
		elseif ($lower == 'package' || $lower == 'subpackage'):
			echo '<dd>' . $apiDoc->packageLink(trim($value)) . '</dd>';
		elseif (is_array($value)):
			foreach ($value as $line):
				echo '<dd>' . $apiDoc->parseText(h($line)) . '</dd>';
			endforeach;
		else:
			echo '<dd>' . $apiDoc->parseText(h($value)) . '</dd>';
		endif; ?>
	<?php endforeach; ?>
</dl>
</div>