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
<div class="sections index">
<h2><?php __('Sections');?></h2>
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
	<th><?php echo $this->Paginator->sort('category_count');?></th>
	<th><?php echo $this->Paginator->sort('hits');?></th>
	<th><?php echo $this->Paginator->sort('created');?></th>
	<th><?php echo $this->Paginator->sort('modified');?></th>
	<th><?php echo $this->Paginator->sort('created_by');?></th>
	<th><?php echo $this->Paginator->sort('modified_by');?></th>
	<th class="actions"><?php __('Actions');?></th>
</tr>
<?php
$i = 0;
foreach ($sections as $section):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<?php echo $section['Section']['id']; ?>
		</td>
		<td>
			<?php echo $section['Section']['title']; ?>
		</td>
		<td>
			<?php echo $section['Section']['image']; ?>
		</td>
		<td>
			<?php echo $section['Section']['image_position']; ?>
		</td>
		<td>
			<?php echo $section['Section']['description']; ?>
		</td>
		<td>
			<?php echo $section['Section']['active']; ?>
		</td>
		<td>
			<?php echo $section['Section']['locked']; ?>
		</td>
		<td>
			<?php echo $section['Section']['locked_since']; ?>
		</td>
		<td>
			<?php echo $section['Section']['locked_by']; ?>
		</td>
		<td>
			<?php echo $section['Section']['ordering']; ?>
		</td>
		<td>
			<?php echo $section['Section']['group_id']; ?>
		</td>
		<td>
			<?php echo $section['Section']['category_count']; ?>
		</td>
		<td>
			<?php echo $section['Section']['hits']; ?>
		</td>
		<td>
			<?php echo $section['Section']['created']; ?>
		</td>
		<td>
			<?php echo $section['Section']['modified']; ?>
		</td>
		<td>
			<?php echo $section['Section']['created_by']; ?>
		</td>
		<td>
			<?php echo $section['Section']['modified_by']; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View', true), array('action' => 'view', $section['Section']['id'])); ?>
			<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $section['Section']['id'])); ?>
			<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $section['Section']['id']), null, sprintf(__('Are you sure you want to delete # %s?', true), $section['Section']['id'])); ?>
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
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Section', true)), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Categories', true)), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Category', true)), array('controller' => 'categories', 'action' => 'add')); ?> </li>
	</ul>
</div>