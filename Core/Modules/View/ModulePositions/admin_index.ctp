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

	$orderHandle = $this->Html->link($this->Design->icon('reorder'), $this->here . '#', array(
		'escape' => false,
		'class' => 'icon reorder'
	));

	$empty = array();
	foreach ($modulePositions as $k => $modulePosition) {
		foreach ($modulePosition['Module'] as &$module) {
			$module = $this->Html->tag('div', implode('', array(
				$orderHandle,
				$this->Design->label($module['name']),
				$this->Html->link($this->Design->icon('minus'), array(
					'controller' => 'modules',
					'action' => 'delete',
					$module['id']
				), array('escape' => false, 'class' => 'icon delete')),
				$this->Html->link($this->Design->icon('edit'), array(
					'controller' => 'modules',
					'action' => 'edit',
					$module['id']
				), array('escape' => false, 'class' => 'icon'))
			)), array(
				'class' => array(
					'module',
					'group-' . $module['group_id']
				)
			));
		}
		$modules = implode('', $modulePosition['Module']);

		$modulePositions[$k] = $this->Html->tag('div', implode('', array(
			$this->Html->tag('h4', implode('', array(
				$this->Infinitas->massActionCheckBox($modulePosition),
				Inflector::humanize($modulePosition['ModulePosition']['name']),
				$this->Html->link($this->Design->icon('add'), array(
					'controller' => 'modules',
					'action' => 'add',
					'ModulePosition.id' => $modulePosition['ModulePosition']['id']
				), array('escape' => false, 'class' => 'icon'))
			))),
			$modules
		)), array(
			'class' => 'thumbnail',
			'data-original-position' => $modulePosition['ModulePosition']['id']
		));

		if(empty($modules)) {
			$empty[] = $modulePositions[$k];
			unset($modulePositions[$k]);
		}
	}

	$groups[0] = __d('users', 'Public');
	foreach ($groups as $k => &$group) {
		$group = $this->Html->link($group, $this->here . '#', array(
			'class' => 'btn btn btn-small',
			'data-group' => $k
		));
	}
	array_unshift($groups, $this->Html->link(__d('modules', 'All'), $this->here . '#', array(
		'class' => 'module-type btn btn-primary btn btn-small disabled',
		'data-group' => 'all'
	)));
	echo $this->Html->tag('div', implode('', $groups), array('class' => 'module-type btn-group'));

	echo $this->Design->arrayToList($modulePositions, array(
		'ul' => 'module-positions thumbnails',
		'li' => 'span3'
	));

	echo $this->Html->tag('h2', __d('modules', 'Unused'));
	echo $this->Design->arrayToList($empty, array(
		'ul' => 'module-positions thumbnails',
		'li' => 'span3 empty'
	));
	return;
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
				<td><?php echo $this->Design->count($modulePosition['ModulePosition']['module_count']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->date($modulePosition['ModulePosition']['modified']); ?>&nbsp;</td>
			</tr> <?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');