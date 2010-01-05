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
<div class="categories index">
<h2><?php __('Categories');?></h2>
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
	<th><?php echo $this->Paginator->sort('image');?></th>
	<th><?php echo $this->Paginator->sort('image_position');?></th>
	<th><?php echo $this->Paginator->sort('description');?></th>
	<th><?php echo $this->Paginator->sort('active');?></th>
	<th><?php echo $this->Paginator->sort('locked');?></th>
	<th><?php echo $this->Paginator->sort('locked_since');?></th>
	<th><?php echo $this->Paginator->sort('locked_by');?></th>
	<th><?php echo $this->Paginator->sort('ordering');?></th>
	<th><?php echo $this->Paginator->sort('group_id');?></th>
	<th><?php echo $this->Paginator->sort('content_count');?></th>
	<th><?php echo $this->Paginator->sort('section_id');?></th>
	<th><?php echo $this->Paginator->sort('views');?></th>
	<th><?php echo $this->Paginator->sort('created');?></th>
	<th><?php echo $this->Paginator->sort('modified');?></th>
	<th><?php echo $this->Paginator->sort('created_by');?></th>
	<th><?php echo $this->Paginator->sort('modified_by');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($categories as $category):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $category['Category']['id']; ?>
		</td>
		<td>
			<?php echo $category['Category']['title']; ?>
		</td>
		<td>
			<?php echo $category['Category']['image']; ?>
		</td>
		<td>
			<?php echo $category['Category']['image_position']; ?>
		</td>
		<td>
			<?php echo $category['Category']['description']; ?>
		</td>
		<td>
			<?php echo $category['Category']['active']; ?>
		</td>
		<td>
			<?php echo $category['Category']['locked']; ?>
		</td>
		<td>
			<?php echo $category['Category']['locked_since']; ?>
		</td>
		<td>
			<?php echo $category['Category']['locked_by']; ?>
		</td>
		<td>
			<?php echo $category['Category']['ordering']; ?>
		</td>
		<td>
			<?php echo $category['Category']['group_id']; ?>
		</td>
		<td>
			<?php echo $category['Category']['content_count']; ?>
		</td>
		<td>
			<?php echo $this->Html->link($category['Section']['title'], array('controller' => 'sections', 'action' => 'view', $category['Section']['id'])); ?>
		</td>
		<td>
			<?php echo $category['Category']['views']; ?>
		</td>
		<td>
			<?php echo $category['Category']['created']; ?>
		</td>
		<td>
			<?php echo $category['Category']['modified']; ?>
		</td>
		<td>
			<?php echo $category['Category']['created_by']; ?>
		</td>
		<td>
			<?php echo $category['Category']['modified_by']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $category['Category']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $category['Category']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $category['Category']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $category['Category']['id'])); ?>
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
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Category', true)), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Sections', true)), array('controller' => 'sections', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Section', true)), array('controller' => 'sections', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Contents', true)), array('controller' => 'contents', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Content', true)), array('controller' => 'contents', 'action' => 'add')); ?> </li>
	</ul>
</div>