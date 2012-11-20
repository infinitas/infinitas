<?php
	/**
	 * Module positions controller
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.modules
	 * @subpackage Infinitas.modules.controllers.module_positions
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	echo $this->Form->create('ModulePosition', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
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
			$this->Paginator->sort('module_count', __d('modules', 'Modules')) => array(
				'style' => 'width:100px'
			),
			$this->Paginator->sort('modified') => array(
				'style' => 'width:100px'
			)
		));

		$i = 0;
		foreach ($modulePositions as $modulePosition) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($modulePosition); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Html->link(Inflector::humanize($modulePosition['ModulePosition']['name']), array(
							'action' => 'edit',
							$modulePosition['ModulePosition']['id']
						));
					?>&nbsp;
				</td>
				<td><?php echo $modulePosition['ModulePosition']['module_count']; ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($modulePosition['ModulePosition']['modified']); ?>&nbsp;</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');