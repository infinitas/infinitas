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
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */
?>
<div class="contents index">
<h2><?php __('Contents');?></h2>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
));
?></p>
<table cellpadding="0" cellspacing="0">
<tr>
	<th><?php echo $this->Paginator->sort('id');?></th>
	<th><?php echo $this->Paginator->sort('title');?></th>
	<th><?php echo $this->Paginator->sort('introduction');?></th>
	<th><?php echo $this->Paginator->sort('body');?></th>
	<th><?php echo $this->Paginator->sort('locked');?></th>
	<th><?php echo $this->Paginator->sort('locked_since');?></th>
	<th><?php echo $this->Paginator->sort('locked_by');?></th>
	<th><?php echo $this->Paginator->sort('ordering');?></th>
	<th><?php echo $this->Paginator->sort('group_id');?></th>
	<th><?php echo $this->Paginator->sort('views');?></th>
	<th><?php echo $this->Paginator->sort('created');?></th>
	<th><?php echo $this->Paginator->sort('modified');?></th>
	<th><?php echo $this->Paginator->sort('created_by');?></th>
	<th><?php echo $this->Paginator->sort('modified_by');?></th>
	<th><?php echo $this->Paginator->sort('category_id');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($contents as $content):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $content['Content']['id']; ?>
		</td>
		<td>
			<?php echo $content['Content']['title']; ?>
		</td>
		<td>
			<?php echo $content['Content']['introduction']; ?>
		</td>
		<td>
			<?php echo $content['Content']['body']; ?>
		</td>
		<td>
			<?php echo $content['Content']['locked']; ?>
		</td>
		<td>
			<?php echo $content['Content']['locked_since']; ?>
		</td>
		<td>
			<?php echo $content['Content']['locked_by']; ?>
		</td>
		<td>
			<?php echo $content['Content']['ordering']; ?>
		</td>
		<td>
			<?php echo $content['Content']['group_id']; ?>
		</td>
		<td>
			<?php echo $content['Content']['views']; ?>
		</td>
		<td>
			<?php echo $content['Content']['created']; ?>
		</td>
		<td>
			<?php echo $content['Content']['modified']; ?>
		</td>
		<td>
			<?php echo $content['Content']['created_by']; ?>
		</td>
		<td>
			<?php echo $content['Content']['modified_by']; ?>
		</td>
		<td>
			<?php echo $this->Html->link($content['Category']['title'], array('controller' => 'categories', 'action' => 'view', $content['Category']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $content['Content']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $content['Content']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $content['Content']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $content['Content']['id'])); ?>
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
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Content', true)), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Categories', true)), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Category', true)), array('controller' => 'categories', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Content Frontpages', true)), array('controller' => 'content_frontpages', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Content Frontpage', true)), array('controller' => 'content_frontpages', 'action' => 'add')); ?> </li>
	</ul>
</div>