<?php
/**
 * Function documentation element
 *
 */
?>
<a id="function-<?php echo $doc->name; ?>"></a>
<div class="function-info">
	<div class="doc-head">
		<h2><?php echo $doc->name; ?></h2>
		<a class="top-link scroll-link" href="#top-functions"><?php __d('api_generator', 'top'); ?></a>
	</div>

	<div class="doc-body">
		<div class="markdown-block"><?php echo $doc->info['comment']['description']; ?></div>
	<dl>
		<?php if (count($doc->params)): ?>
		<dt><?php __d('api_generator', 'Parameters:'); ?></dt>
		<dd>
			<table>
				<tbody>
				<?php $i = 0; ?>
				<?php foreach ($doc->params as $name => $paramInfo): ?>
					<tr class="<?php echo ($i % 2) ? 'even' : 'odd'; ?>">
						<td>$<?php echo $name; ?></td>
						<td><?php echo $paramInfo['type']; ?></td>
						<td><?php echo $paramInfo['comment']; ?></td>
						<td><?php echo ($paramInfo['optional']) ? 'optional' : 'required'; ?></td>
						<td><?php echo ($paramInfo['hasDefault']) ? var_export($paramInfo['default'], true) : __d('api_generator', '(no default)', true); ?></td>
					</tr>
					<?php $i++;?>
				<?php endforeach; ?>
				</tbody>
			</table>
		</dd>
		<?php endif; ?>
		
		<dt><?php __d('api_generator', 'Function defined in file:'); ?></dt>
		<dd><?php 
			echo $apiDoc->fileLink($doc->info['declaredInFile']);
			$pseudoClass = basename($doc->info['declaredInFile']);
			if ($apiDoc->inClassIndex($pseudoClass)):
				__d('api_generator', ' on line ');
				echo $html->link($doc->info['startLine'], array(
					'controller' => 'api_classes',
					'action' => 'view_source', 
					$pseudoClass,
					'#line-'. $doc->info['startLine']
				));
			endif;
		?> </dd>
	</dl>
	<?php 
		unset($doc->info['comment']['tags']['param']);
		echo $this->element('tag_block', array('tags' => $doc->info['comment']['tags'])); 
	?>
	</div>
</div>