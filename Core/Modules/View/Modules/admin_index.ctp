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
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   sort
	 * @subpackage	sort.comments
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since		 0.5a
	 */

	echo $this->Form->create(false, array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'copy',
		'move',
		'toggle',
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
			$this->Paginator->sort('Theme.name', __d('modules', 'Theme')),
			$this->Paginator->sort('plugin'),
			$this->Paginator->sort('Position.name', __d('modules', 'Position')),
			$this->Paginator->sort('author'),
			$this->Paginator->sort('Group.name', __d('modules', 'Display to')) => array(
				'style' => 'width:75px;'
			),
			$this->Paginator->sort('Order') => array(
				'style' => 'width:50px;'
			),
			$this->Paginator->sort('active', __d('modules', 'Status')) => array(
				'style' => 'width:50px;'
			)
		));

		foreach ($modules as $module) {
			?>
				<tr>
					<td><?php echo $this->Infinitas->massActionCheckBox($module); ?>&nbsp;</td>
					<td>
						<?php
							echo $this->Html->link(Inflector::humanize($module['Module']['name']), array(
								'action' => 'edit',
								$module['Module']['id']
							));
						?>&nbsp;
					</td>
					<td>
						<?php
							$config = array();
							if (!empty($module['Theme']['name'])) {
								$config = array(
									'class' => 'label-info'
								);
							} else {
								$module['Theme']['name'] = __d('modules', 'Any');
							}
							echo $this->Design->label(Inflector::humanize($module['Theme']['name']), $config);
						?>&nbsp;
					</td>
					<td><?php echo Inflector::humanize($module['Module']['plugin']); ?>&nbsp;</td>
					<td><?php echo Inflector::humanize($module['Position']['name']); ?>&nbsp;</td>
					<td>
						<?php
							if (!empty($module['Module']['url'])) {
								$module['Module']['author'] = $this->Html->link($module['Module']['author'], $module['Module']['url'], array(
									'target' => '_blank'
								));
							}
							echo $module['Module']['author'];
							echo $this->Design->license($module['Module']['licence']);
						?>&nbsp;
					</td>
					<td><?php echo $module['Group']['name']; ?>&nbsp;</td>
					<td><?php echo $this->Infinitas->ordering($module['Module']['id'], $module['Module']['ordering'], 'Modules.Module'); ?>&nbsp;</td>
					<td>
						<?php
							if ($module['Module']['core']) {
								echo $this->Html->link($this->Design->icon('locked'), $this->here . '#', array(
									'class' => 'icon locks',
									'title' => __d('modules', 'This is a core module'),
									'escape' => false
								));
							}
							echo $this->Infinitas->status($module['Module']['active']), $this->Locked->display($module);
						?>&nbsp;
					</td>
				</tr>
			<?php
		}
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');