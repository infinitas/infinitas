<?php
	/**
	 * View for managing trash
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Trash.View
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.5a
	 *
	 * @author Carl Sutton <dogmatic69@infinitas-cms.org>
	 */

	echo $this->Form->create(null, array('url' => array('controller' => 'trash', 'action' => 'mass')));
		echo $this->Infinitas->adminIndexHead($filterOptions, array(
			'restore',
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
			$this->Paginator->sort('model', __d('trash', 'Type')) => array(
				'class' => 'xlarge'
			),
			$this->Paginator->sort('deleted') => array(
				'class' => 'date'
			),
			$this->Paginator->sort('Deleter.name', __d('trash', 'Deleted By')) => array(
				'class' => 'larger'
			)
		));

		foreach ($trashed as $trash) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($trash); ?>&nbsp;</td>
				<td><?php echo Inflector::humanize($trash['Trash']['name']); ?>&nbsp;</td>
				<td>
					<?php
						$type = pluginSplit($trash['Trash']['model']);
						if(isset($type[1])) {
							$type[1] = prettyName($type[1]);
						}
						echo implode(' ', array_unique($type));
					?>&nbsp;
				</td>
				<td><?php echo $this->Infinitas->date($trash['Trash']['deleted']); ?></td>
				<td>
					<?php
						echo $this->Html->link($trash['User']['username'], array(
							'plugin' => 'users',
							'controller' => 'users',
							'action' => 'edit',
							$trash['User']['id']
						));
					?>&nbsp;
				</td>
			</tr> <?php
		} ?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');