<?php
/**
 * Properties Element
 *
 */
echo $apiUtils->element('before_properties');
$apiUtils->sortByName($doc->properties);
?>
<div class="doc-block">
	<div class="doc-head"><h3><?php __d('api_generator', 'Properties:'); ?></h3></div>
	<div class="doc-body">
	<?php if (!empty($doc->properties)): ?>
<?php if (empty($isSearch)): ?>
		<span class="doc-controls">
			<a href="#" id="hide-parent-properties"><?php __d('api_generator', 'Show/Hide parent properties'); ?></a>
		</span>
<?php endif; ?>
		<table>
		<?php $i = 0; ?>
		<?php foreach ($doc->properties as $prop): ?>
			<?php
			if ($apiDoc->excluded($prop['access'], 'property')) :
				continue;
			endif;
			$definedInThis = ($prop['declaredInClass'] == $doc->classInfo['name']);
			?>
			<tr class="<?php echo ($i % 2) ? 'even' : 'odd'; ?> <?php echo $definedInThis ? '' : 'parent-property'; ?>">
				<td class="access <?php echo $prop['access']; ?>"><span><?php echo $prop['access']; ?></span></td>
				<td><?php echo $prop['name']; ?></td>
				<td class="markdown-block"><?php echo h($prop['comment']['description']); ?></td>
			</tr>
			<?php $i++;?>
		<?php endforeach; ?>
		</table>
	<?php endif; ?>
	</div>
</div>
<?php echo $apiUtils->element('after_properties'); ?>