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

    echo $this->Form->create('MenuItem', array('action' => 'mass'));
	echo $this->Infinitas->adminIndexHead($filterOptions, array(
		'add',
		'edit',
		'toggle',
		'copy',
		'move',
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
			$this->Paginator->sort('Menu') => array(
				'style' => 'width:100px;'
			),
			$this->Paginator->sort('Group.name', __d('menus', 'Access')) => array(
				'style' => 'width:90px;'
			),
			__d('menus', 'Order') => array(
				'style' => 'width:50px;'
			),
			$this->Paginator->sort('active', __d('menus', 'Status')) => array(
				'style' => 'width:50px;'
			),
			__d('menus', 'Actions') => array(
				'style' => 'width:50px;'
			)
		));
		$MenuItem = ClassRegistry::init('Menu.MenuItem');

		foreach ($menuItems as $menuItem) { ?>
			<tr>
				<td><?php echo $this->Infinitas->massActionCheckBox($menuItem); ?>&nbsp;</td>
				<td>
					<?php
						$paths = count($MenuItem->getPath($menuItem['MenuItem']['id']))-1;
						$links = array();

						if ($paths > 1) {
							echo '<b>', str_repeat('- ', $paths-1), ' |</b> ';
						}

						if ($menuItem['MenuItem']['name'] == '--') {
							$menuItem['MenuItem']['name'] = $this->Html->tag('span', __d('menus', 'Separator'), array(
								'class' => 'label'
							));
						}
						if ($menuItem['MenuItem']['class'] == 'nav-header') {
							$menuItem['MenuItem']['name'] = $this->Html->tag('span', $menuItem['MenuItem']['name'], array(
								'class' => 'label label-info'
							));
						}
						echo $this->Html->link($menuItem['MenuItem']['name'], array(
							'action' => 'edit',
							$menuItem['MenuItem']['id']
						), array('escape' => false));
					?>&nbsp;
				</td>
				<td><?php echo Inflector::humanize($menuItem['Menu']['name']); ?>&nbsp;</td>
				<td>
					<?php
						if (empty($menuItem['Group']['name'])) {
							echo __d('menus', 'Public');
						} else {
							echo $this->Html->tag('span', $menuItem['Group']['name'], array('class' => 'label label-warning'));
						}
					?>&nbsp;
				</td>
				<td><?php echo $this->Infinitas->treeOrdering($menuItem['MenuItem']); ?>&nbsp;</td>
				<td><?php echo $this->Infinitas->status($menuItem['MenuItem']['active']); ?>&nbsp;</td>
				<td>
					<?php
						echo $this->Image->image('actions', 'add', array(
							'width' => '16px',
							'url' => array(
								'action' => 'add',
								'parent_id' => $menuItem['MenuItem']['id']
							)
						));
					?>&nbsp;
				</td>
			</tr> <?php
		}
		unset($MenuItem);
	?>
</table>
<?php
	echo $this->Form->end();
	echo $this->element('pagination/admin/navigation');