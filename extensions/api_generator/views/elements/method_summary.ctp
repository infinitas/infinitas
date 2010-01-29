<?php
/**
 * Method Summary Element
 *
 */
echo $apiUtils->element('before_method_summary');
$apiUtils->sortByName($doc->methods); 
$title = (empty($isSearch)) ? __d('api_generator', 'Method Summary:', true) : __d('api_generator', 'Methods:', true);
?>
<div class="doc-block">
	<a id="top-<?php echo $doc->name; ?>"></a>
	<div class="doc-head"><h3><?php echo $title; ?></h3></div>
	<div class="doc-body">
<?php if (empty($isSearch)): ?>
		<span class="doc-controls">
			<a href="#" id="hide-parent-methods"><?php __d('api_generator', 'Show/Hide parent methods'); ?></a>
		</span>
<?php endif; ?>
		<table class="summary">
			<tbody>
			<?php $i = 0; ?>
			<?php foreach ($doc->methods as $method): ?>
				<?php
				if ($apiDoc->excluded($method['access'], 'method')) :
					continue;
				endif;
				$definedInThis = ($method['declaredInClass'] == $doc->classInfo['name']);
				?>
				<tr class="<?php echo ($i % 2) ? 'even' : 'odd'; ?> <?php echo $definedInThis ? '' : 'parent-method'; ?>">
					<td class="access <?php echo $method['access']; ?>"><span><?php echo $method['access']; ?></span></td>
					<td>
					<?php
						if (empty($isSearch)):
							echo $html->link($method['signature'],
								'#method-' . $doc->name . $method['name'],
								array('class' => 'scroll-link')
							);
						else:
							echo $html->link($method['signature'],
								array('action' => 'view_class', $apiDoc->slug($doc->name),
								'#' => 'method-' . $doc->name . $method['name']),
								array('class' => 'scroll-link')
							);
						endif;
					?>
					</td>
				</tr>
				<?php $i++;?>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
<?php echo $apiUtils->element('after_method_summary'); ?>