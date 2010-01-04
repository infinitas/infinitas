<?php
    /**
     * Comment Template.
     *
     * @todo -c Implement .this needs to be sorted out.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://www.dogmatic.co.za
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */
?>
<div class="contentFrontpages index">
<h2><?php __('Content Frontpages');?></h2>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('content_id');?></th>
	<th><?php echo $this->Paginator->sort('ordering');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($contentFrontpages as $contentFrontpage):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $this->Html->link($contentFrontpage['Content']['title'], array('controller' => 'contents', 'action' => 'view', $contentFrontpage['Content']['id'])); ?>
		</td>
		<td>
			<?php echo $contentFrontpage['ContentFrontpage']['ordering']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $contentFrontpage['ContentFrontpage']['content_id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $contentFrontpage['ContentFrontpage']['content_id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $contentFrontpage['ContentFrontpage']['content_id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $contentFrontpage['ContentFrontpage']['content_id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<div class="paging">
	<?php echo $this->Paginator->prev('<< '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 | 	<?php echo $this->Paginator->numbers();?>
	<?php echo $this->Paginator->next(__('next', true).' >>', array(), null, array('class' => 'disabled'));?>
</div>
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Content Frontpage', true)), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Contents', true)), array('controller' => 'contents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Content', true)), array('controller' => 'contents', 'action' => 'add')); ?> </li>
	</ul>
</div>