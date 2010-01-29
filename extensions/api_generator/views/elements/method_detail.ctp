<?php
/**
 * Method Detail element
 *
 */
echo $apiUtils->element('before_method_detail');
?>
<?php foreach ($doc->methods as $method):
	if ($apiDoc->excluded($method['access'], 'method')) :
		continue;
	endif;
	$definedInThis = ($method['declaredInClass'] == $doc->classInfo['name']);
?>
<div class="doc-block <?php echo $definedInThis ? '' : 'parent-method'; ?>">
	<a id="method-<?php echo $doc->name . $method['name']; ?>"></a>
	<div class="doc-head">
		<h2 class="<?php echo $method['access'] ?>"><?php echo $method['name']; ?></h2>
		<a class="top-link scroll-link" href="#top-<?php echo $doc->name; ?>"><?php __d('api_generator', 'top'); ?></a>
	</div>

	<div class="doc-body">
		<div class="markdown-block"><?php echo $apiDoc->parseText(h($method['comment']['description'])); ?></div>
	<dl>
		<?php if (count($method['args'])): ?>
		<dt><?php __d('api_generator', 'Parameters:'); ?></dt>
		<dd>
			<table>
				<tbody>
				<?php $i = 0; ?>
				<?php foreach ($method['args'] as $name => $paramInfo): ?>
					<tr class="<?php echo ($i % 2) ? 'even' : 'odd'; ?>">
						<td>$<?php echo $name; ?></td>
						<td><?php echo $paramInfo['type']; ?></td>
						<td><?php echo h($paramInfo['comment']); ?></td>
						<td><?php echo ($paramInfo['optional']) ? 'optional' : 'required'; ?></td>
						<td><?php echo ($paramInfo['hasDefault']) ? var_export($paramInfo['default'], true) : __d('api_generator', '(no default)', true); ?></td>
					</tr>
					<?php $i++;?>
				<?php endforeach; ?>
				</tbody>
			</table>
		</dd>
		<?php endif; ?>
		
		<dt><?php __d('api_generator', 'Method defined in class:'); ?></dt>
		<dd><?php echo $apiDoc->classLink($method['declaredInClass']); ?></dd>
		
		<dt><?php __d('api_generator', 'Method defined in file:'); ?></dt>
		<dd><?php 
			echo $apiDoc->fileLink($method['declaredInFile']);
			
			if ($apiDoc->inClassIndex($method['declaredInClass'])):
				__d('api_generator', ' on line ');
				echo $html->link($method['startLine'], array(
					'controller' => 'api_classes',
					'action' => 'view_source', 
					$apiDoc->slug($method['declaredInClass']),
					'#line-'. $method['startLine']
				));
			endif;
		?> </dd>
		</dl>
		<?php echo $this->element('tag_block', array('tags' => $method['comment']['tags'])); ?>
	</div>
</div>
<?php
endforeach;
echo $apiUtils->element('after_method_detail');
?>