<?php
/**
 * Api Search results
 *
 */
$apiDoc->setClassIndex($classIndex);
?>
<h1><?php echo sprintf(__d('api_generator', 'Search Results for "%s"', true), $this->passedArgs[0]); ?></h1>
<?php if (empty($docs)): ?>
	<p class="error"><?php __d('api_generator', 'Your search returned no results'); ?></p>
<?php return; 
endif; ?>

<ul id="search-results">
<?php foreach ($docs as $result):
	if (isset($result['function'])):
		foreach($result['function'] as $name => $doc): ?>
			<li class="doc-block function-info">
				<h2><?php echo $apiDoc->fileLink($doc->info['declaredInFile']); ?></h2>
				<div class="doc-body">
					<table class="summary">
						<tbody>
							<tr class="even">
								<td class="access public"><span><?php __d('api_generator', 'public'); ?></span></td>
								<td>
								<?php
									echo $html->link($doc->info['signature'],
											array('action' => 'view_file', $apiDoc->trimFileName($doc->info['declaredInFile']),
											'#' => 'function-' . $doc->name),
											array('class' => 'scroll-link')
										);
								?>
								</td>
							</tr>
						</tbody>
					</table>

				</div>
			</li>
<?php	endforeach;
	elseif (isset($result['class'])) :
		foreach ($result['class'] as $name => $doc): ?>
			<li class="doc-block class-info">
				<h2><?php echo $apiDoc->classLink($doc->name, array(), array('class' => false)); ?></h2><?php
			if ($doc->properties):
				echo $this->element('properties', array('doc' => $doc, 'isSearch' => true));
			endif;
		
			if ($doc->methods):
				echo $this->element('method_summary', array('doc' => $doc, 'isSearch' => true));
			endif;
	?>
			</li>
	<?php	
		endforeach;
	endif;
endforeach;
?>
</ul>