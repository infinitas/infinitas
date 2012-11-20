<?php
	/**
	 * View for managing groups
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton (dogmatic69)
	 *
	 * @link http://infinitas-cms.org/users
	 * @package Infinitas.Users.View
	 * @license http://infinitas-cms.org/mit-license The MIT License
	 * @since 0.9b1
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
	 */

	echo $this->Form->create(null, array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'toggle',
		'copy',
		'delete'
	));
?>
<table class="listing">
	<?php
		echo $this->Infinitas->adminTableHeader(array(
			$this->Form->checkbox('all') => array(
				'class' => 'first'
			),
			$this->Paginator->sort('name'),
			$this->Paginator->sort('parent_id') => array(
				'class' => 'larger'
			),
			$this->Paginator->sort('modified') => array(
				'class' => 'date'
			),
		));

		foreach ($groups as $group) { ?>
			<tr>
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
				<td><?php echo $this->Infinitas->date($group['Group']['modified']); ?></td>
			</tr><?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');