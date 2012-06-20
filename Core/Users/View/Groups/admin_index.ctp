<?php
	/**
	 * @brief Add some documentation for this admin_index form.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link		  http://infinitas-cms.org/users
	 * @package	   users.views.admin_index
	 * @license	   http://infinitas-cms.org/mit-license The MIT License
	 * @since 0.9b1
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
	echo $this->Form->create('Group', array('action' => 'mass'));

	$massActions = $this->Infinitas->massActionButtons(
		array(
			'add',
			'edit',
			'toggle',
			'copy',
			'delete',

			// other methods available
			// 'unlock',
		)
	);

	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);
?>
<div class="table">
	<table class="listing" cellpadding="0" cellspacing="0">
		<?php
			echo $this->Infinitas->adminTableHeader(
				array(
					$this->Form->checkbox('all') => array(
						'class' => 'first',
						'style' => 'width:25px;'
					),
					$this->Paginator->sort('name'),
					$this->Paginator->sort('parent_id'),
					$this->Paginator->sort('created') => array(
						'style' => 'width:75px;'
					),
					$this->Paginator->sort('modified') => array(
						'style' => 'width:75px;'
					),
				)
			);

			foreach ($groups as $group) { ?>
				<tr class="<?php echo $this->Infinitas->rowClass(); ?>">
					<td><?php echo $this->Infinitas->massActionCheckBox($group); ?>&nbsp;</td>
					<td>
						<?php 
							echo $this->Html->link(
								$group['Group']['name'],
								array(
									'action' => 'edit',
									$group['Group']['id']
								)
							);
						?>&nbsp;
					</td>
					<td><?php echo $group['Group']['parent_id']; ?>&nbsp;</td>
					<td><?php echo $this->Time->niceShort($group['Group']['created']); ?>&nbsp;</td>
					<td><?php echo $this->Time->niceShort($group['Group']['modified']); ?>&nbsp;</td>
				</tr><?php
			}
		?>
	</table>
	<?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>